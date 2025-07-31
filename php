#!/bin/bash

# Configuration with environment variable support
CONTAINER_BASE_NAME="${PHP_CLI_CONTAINER_NAME:-php-cli}"
DOCKER_IMAGE="${PHP_CLI_IMAGE:-php:8.3-cli}"
PLATFORM="${PHP_CLI_PLATFORM:-}"
LOG_FILE="${PHP_CLI_LOG_FILE:-${HOME}/.local/bin/php.log}"
EXTRA_VOLUMES="${PHP_CLI_VOLUMES:-}"
EXTRA_PORTS="${PHP_CLI_PORTS:-}"
NETWORK="${PHP_CLI_NETWORK:-}"

# Generate unique container name based on current directory
generate_container_name() {
    local current_dir="$(pwd)"

    # Cross-platform hash generation (macOS and Linux compatible)
    if command -v shasum >/dev/null 2>&1; then
        # macOS
        local dir_hash=$(echo "$current_dir" | shasum -a 256 | cut -c1-8)
    elif command -v sha256sum >/dev/null 2>&1; then
        # Linux
        local dir_hash=$(echo "$current_dir" | sha256sum | cut -c1-8)
    else
        # Fallback: use a simpler hash based on directory name
        local dir_hash=$(echo "$current_dir" | cksum | cut -d' ' -f1)
    fi

    echo "${CONTAINER_BASE_NAME}-${dir_hash}"
}

# Set container name and working directory
CONTAINER_NAME=$(generate_container_name)
WORKDIR="$(pwd)"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function for logging
log_message() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $*" >> "$LOG_FILE"
}

# Function to show help
show_help() {
    echo -e "${BLUE}Usage: $(basename "$0") [COMMAND] [ARGUMENTS]${NC}"
    echo ""
    echo -e "${YELLOW}Available commands:${NC}"
    echo "  start     - Start the PHP container"
    echo "  stop      - Stop the PHP container"
    echo "  restart   - Restart the PHP container"
    echo "  status    - Show container status"
    echo "  logs      - Show container logs"
    echo "  shell     - Enter container shell"
    echo "  config    - Show current configuration"
    echo "  debug     - Show debug information"
    echo "  list      - List all php-cli containers"
    echo "  help      - Show this help"
    echo ""
    echo -e "${YELLOW}Examples:${NC}"
    echo "  $(basename "$0") start"
    echo "  $(basename "$0") -v"
    echo "  $(basename "$0") composer install"
    echo "  $(basename "$0") artisan migrate"
    echo ""
    echo -e "${YELLOW}Environment Variables:${NC}"
    echo "  PHP_CLI_CONTAINER_NAME  - Container base name (default: php-cli)"
    echo "  PHP_CLI_IMAGE          - Docker image (default: php:8.3-cli)"
    echo "  PHP_CLI_PLATFORM       - Platform (optional, e.g.: linux/amd64)"
    echo "  PHP_CLI_LOG_FILE       - Log file path (default: ~/.local/bin/php.log)"
    echo "  PHP_CLI_VOLUMES        - Extra volumes (format: -v /host:/container)"
    echo "  PHP_CLI_PORTS          - Port mappings (format: -p 8080:80)"
    echo "  PHP_CLI_NETWORK        - Docker network (format: --network mynet)"
    echo ""
    echo -e "${YELLOW}Note:${NC} Container name is automatically generated as: ${GREEN}${CONTAINER_BASE_NAME}-<hash>${NC}"
    echo "      Hash is based on current directory path for project isolation."
}

# Function to show current configuration
show_config() {
    echo -e "${BLUE}Current Configuration:${NC}"
    echo -e "  Container Base Name: ${GREEN}$CONTAINER_BASE_NAME${NC}"
    echo -e "  Generated Name:      ${GREEN}$CONTAINER_NAME${NC}"
    echo -e "  Docker Image:        ${GREEN}$DOCKER_IMAGE${NC}"
    echo -e "  Platform:            ${GREEN}${PLATFORM:-auto-detect}${NC}"
    echo -e "  Log File:            ${GREEN}$LOG_FILE${NC}"
    echo -e "  Working Dir:         ${GREEN}$WORKDIR${NC}"
    echo -e "  Extra Volumes:       ${GREEN}${EXTRA_VOLUMES:-none}${NC}"
    echo -e "  Extra Ports:         ${GREEN}${EXTRA_PORTS:-none}${NC}"
    echo -e "  Network:             ${GREEN}${NETWORK:-default}${NC}"
}

# Function to show debug information
show_debug() {
    echo -e "${BLUE}System Debug Information:${NC}"
    echo -e "  Host Architecture: ${GREEN}$(uname -m)${NC}"
    echo -e "  Host OS:          ${GREEN}$(uname -s)${NC}"

    if command -v sw_vers >/dev/null 2>&1; then
        echo -e "  macOS Version:    ${GREEN}$(sw_vers -productVersion)${NC}"
    fi

    echo -e "  Docker Version:   ${GREEN}$(docker --version 2>/dev/null || echo 'Not available')${NC}"

    echo ""
    echo -e "${BLUE}Container Information:${NC}"
    if container_running; then
        echo -e "  Container Status: ${GREEN}Running${NC}"
        echo -e "  Container Arch:   ${GREEN}$(docker exec "$CONTAINER_NAME" uname -m 2>/dev/null || echo 'Unknown')${NC}"
        echo -e "  PHP Binary Test:  ${GREEN}$(docker exec "$CONTAINER_NAME" which php 2>/dev/null || echo 'Not found')${NC}"

        echo ""
        echo -e "${YELLOW}Testing PHP execution...${NC}"
        if docker exec "$CONTAINER_NAME" php --version >/dev/null 2>&1; then
            echo -e "  PHP Test:         ${GREEN}✓ Working${NC}"
        else
            echo -e "  PHP Test:         ${RED}✗ Failed (Segmentation fault or other error)${NC}"
            echo ""
            echo -e "${YELLOW}Suggested solutions:${NC}"
            echo "    1. Try with platform: ${BLUE}PHP_CLI_PLATFORM=linux/amd64 php restart${NC}"
            echo "    2. Try different image: ${BLUE}PHP_CLI_IMAGE=php:8.3-cli php restart${NC}"
            echo "    3. Try both together: ${BLUE}PHP_CLI_PLATFORM=linux/amd64 PHP_CLI_IMAGE=php:8.3-cli php restart${NC}"
            echo "    4. Check Docker Desktop settings for Rosetta emulation"
        fi
    else
        echo -e "  Container Status: ${RED}Not running${NC}"
        echo -e "${YELLOW}Run 'php start' to start the container first${NC}"
    fi
}

# Function to list all php-cli containers
list_containers() {
    echo -e "${BLUE}All PHP CLI Containers:${NC}"
    echo ""

    local all_containers=$(docker ps -a --filter "name=${CONTAINER_BASE_NAME}-" --format "{{.Names}}\t{{.Status}}\t{{.Image}}")

    if [ -n "$all_containers" ]; then
        echo -e "${YELLOW}Container Name\t\t\tStatus\t\t\tImage${NC}"
        echo "────────────────────────────────────────────────────────────────"
        echo "$all_containers" | while IFS=$'\t' read name status image; do
            if [ "$name" = "$CONTAINER_NAME" ]; then
                echo -e "${GREEN}$name\t$status\t$image${NC} ${BLUE}(current directory)${NC}"
            else
                echo -e "${YELLOW}$name\t$status\t$image${NC}"
            fi
        done
    else
        echo -e "${RED}No php-cli containers found${NC}"
    fi

    echo ""
    echo -e "${BLUE}Current directory container: ${GREEN}${CONTAINER_NAME}${NC}"
}

# Function to check if container exists
container_exists() {
    docker ps -a --format "table {{.Names}}" | grep -q "^${CONTAINER_NAME}$"
}

# Function to check if container is running
container_running() {
    docker ps --format "table {{.Names}}" | grep -q "^${CONTAINER_NAME}$"
}

# Function to start container
start_container() {
    if container_running; then
        echo -e "${YELLOW}Container is already running${NC}"
        return 0
    fi

    if container_exists; then
        echo -e "${BLUE}Starting existing container...${NC}"
        docker start "$CONTAINER_NAME" &>/dev/null
    else
        echo -e "${BLUE}Creating and starting new container...${NC}"

        # Build docker run command with optional parameters
        DOCKER_CMD="docker run --rm --name $CONTAINER_NAME -i -t -d"
        DOCKER_CMD="$DOCKER_CMD --workdir $WORKDIR"
        DOCKER_CMD="$DOCKER_CMD --volume $WORKDIR:$WORKDIR"

        # Add platform only if explicitly set
        if [ -n "$PLATFORM" ]; then
            DOCKER_CMD="$DOCKER_CMD --platform $PLATFORM"
        fi

        # Add extra volumes if specified
        if [ -n "$EXTRA_VOLUMES" ]; then
            DOCKER_CMD="$DOCKER_CMD $EXTRA_VOLUMES"
        fi

        # Add port mappings if specified
        if [ -n "$EXTRA_PORTS" ]; then
            DOCKER_CMD="$DOCKER_CMD $EXTRA_PORTS"
        fi

        # Add network if specified
        if [ -n "$NETWORK" ]; then
            DOCKER_CMD="$DOCKER_CMD $NETWORK"
        fi

        DOCKER_CMD="$DOCKER_CMD --entrypoint /bin/sh $DOCKER_IMAGE"

        eval "$DOCKER_CMD" &>/dev/null
    fi

    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Container started successfully${NC}"
        log_message "Container started"
    else
        echo -e "${RED}Error starting container${NC}"
        log_message "Error starting container"
        exit 1
    fi
}

# Function to stop container
stop_container() {
    if ! container_running; then
        echo -e "${YELLOW}Container is not running${NC}"
        return 0
    fi

    echo -e "${BLUE}Stopping container...${NC}"
    docker stop "$CONTAINER_NAME" &>/dev/null

    if [ $? -eq 0 ]; then
        echo -e "${GREEN}Container stopped successfully${NC}"
        log_message "Container stopped"
    else
        echo -e "${RED}Error stopping container${NC}"
        log_message "Error stopping container"
        exit 1
    fi
}

# Function to show status
show_status() {
    echo -e "${BLUE}Status for current directory: ${GREEN}$(pwd)${NC}"
    echo -e "Expected container name: ${GREEN}${CONTAINER_NAME}${NC}"
    echo ""

    if container_running; then
        echo -e "${GREEN}Container is running${NC}"
        docker ps --filter "name=${CONTAINER_NAME}" --format "table {{.ID}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}"
    elif container_exists; then
        echo -e "${YELLOW}Container exists but is not running${NC}"
        docker ps -a --filter "name=${CONTAINER_NAME}" --format "table {{.ID}}\t{{.Image}}\t{{.Status}}"
    else
        echo -e "${RED}Container does not exist for this directory${NC}"
        echo ""
        echo -e "${BLUE}Other php-cli containers running:${NC}"
        local other_containers=$(docker ps --filter "name=${CONTAINER_BASE_NAME}-" --format "{{.Names}}" | grep -v "^${CONTAINER_NAME}$")
        if [ -n "$other_containers" ]; then
            echo "$other_containers" | while read container; do
                echo -e "  ${YELLOW}$container${NC} (from different directory)"
            done
        else
            echo -e "${YELLOW}No other php-cli containers found${NC}"
        fi
    fi
}

# Function to show logs
show_logs() {
    if ! container_exists; then
        echo -e "${RED}Container does not exist for this directory${NC}"
        echo -e "${BLUE}Expected container: ${GREEN}${CONTAINER_NAME}${NC}"
        echo ""
        echo -e "${YELLOW}Use 'php list' to see all available containers${NC}"
        exit 1
    fi

    echo -e "${BLUE}Logs for container: ${GREEN}${CONTAINER_NAME}${NC}"
    docker logs "$CONTAINER_NAME" "$@"
}

# Function to enter shell
enter_shell() {
    if ! container_running; then
        echo -e "${YELLOW}Container is not running. Starting automatically...${NC}"
        start_container
        if [ $? -ne 0 ]; then
            echo -e "${RED}Failed to start container${NC}"
            exit 1
        fi
    fi

    docker exec -it "$CONTAINER_NAME" /bin/sh
}

# Function to execute PHP command
execute_php() {
    if ! container_running; then
        echo -e "${YELLOW}Container is not running. Starting automatically...${NC}"
        start_container
        if [ $? -ne 0 ]; then
            echo -e "${RED}Failed to start container${NC}"
            exit 1
        fi
    fi

    log_message "Executing: php $*"
    docker exec -it "$CONTAINER_NAME" php "$@"
}

# Create log directory if it does not exist
mkdir -p "$(dirname "$LOG_FILE")"

# Check if Docker is running
if ! docker info &>/dev/null; then
    echo -e "${RED}Docker is not running or not accessible${NC}"
    exit 1
fi

# Process arguments
ACTION="$1"

case "${ACTION}" in
    "start")
        start_container
        ;;
    "stop")
        stop_container
        ;;
    "restart")
        stop_container
        start_container
        ;;
    "status")
        show_status
        ;;
    "config")
        show_config
        ;;
    "debug")
        show_debug
        ;;
    "list")
        list_containers
        ;;
    "logs")
        shift
        show_logs "$@"
        ;;
    "shell" | "bash" | "sh")
        enter_shell
        ;;
    "help" | "--help" | "-h")
        show_help
        ;;
    "")
        echo -e "${RED}No command specified${NC}"
        show_help
        exit 1
        ;;
    *)
        execute_php "$@"
        ;;
esac
