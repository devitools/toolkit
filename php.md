# 🐘 PHP Docker CLI

A Docker abstraction that allows you to easily use PHP through containers with automatic per-project isolation.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Commands](#commands)
- [Configuration](#configuration)
- [Examples](#examples)
- [Troubleshooting](#troubleshooting)

## 🎯 Overview

The PHP tool is a bash script that wraps Docker to provide seamless PHP execution without requiring local PHP installation. It automatically creates isolated containers per project directory, making it perfect for working with multiple PHP projects simultaneously.

## ✨ Features

- **🔒 Project Isolation**: Each directory gets its own container based on path hash
- **🐳 Zero Local Setup**: Uses Docker containers, no PHP installation needed
- **📦 Package Management**: Full support for Composer and other PHP tools
- **🚀 Auto-Start**: Containers start automatically when needed
- **🔧 Configurable**: Extensive configuration via environment variables
- **📝 Logging**: Detailed operation logs for debugging
- **🌐 Framework Support**: Works perfectly with Laravel, Symfony, etc.

## 🚀 Installation

### Prerequisites
- Docker must be installed and running on your system
- Compatible with macOS, Linux, and Windows (with WSL)

### Installation Steps

1. **Make the script executable:**
   ```bash
   chmod +x php
   ```

2. **Add to PATH for global access:**
   
   **Option A: Copy to a directory already in PATH**
   ```bash
   sudo cp php /usr/local/bin/
   ```
   
   **Option B: Add ~/.local/bin to your PATH (Recommended)**
   ```bash
   # Add to your shell profile (~/.bashrc, ~/.zshrc, etc.)
   echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc
   source ~/.bashrc
   ```
   
   **Option C: Create symbolic link**
   ```bash
   sudo ln -s $(pwd)/php /usr/local/bin/php
   ```

3. **Verify installation:**
   ```bash
   php --version
   # or
   php status
   ```

### PATH Configuration

To make the `php` tool available globally in your terminal, ensure `~/.local/bin` is in your PATH:

```bash
# For Bash users
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc && source ~/.bashrc

# For Zsh users  
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.zshrc && source ~/.zshrc

# For Fish users
fish_add_path ~/.local/bin
```

### Docker Setup Verification

```bash
# Verify Docker is running
docker info

# Test PHP container creation
php -v
```

## ⚡ Quick Start

```bash
# Check PHP version (auto-starts container)
php -v

# Install dependencies with Composer
php composer install

# Run Laravel Artisan commands
php artisan migrate

# Enter the container shell
php shell

# Check container status
php status
```

## 📚 Commands

### Container Management

| Command | Description |
|---------|-------------|
| `php start` | Start the PHP container |
| `php stop` | Stop the PHP container |
| `php restart` | Restart the PHP container |
| `php status` | Show container status |
| `php logs` | Show container logs |

### Development

| Command | Description |
|---------|-------------|
| `php shell` | Enter container bash shell |
| `php <command>` | Execute any PHP command |
| `php composer <args>` | Run Composer commands |
| `php artisan <args>` | Run Laravel Artisan commands |

### Information

| Command | Description |
|---------|-------------|
| `php config` | Show current configuration |
| `php debug` | Show debug information |
| `php list` | List all php-cli containers |
| `php help` | Show help information |

## ⚙️ Configuration

Configure the tool using environment variables:

### Basic Configuration

```bash
# Container base name (default: php-cli)
export PHP_CLI_CONTAINER_NAME="my-php"

# Docker image (default: php:8.3-cli)
export PHP_CLI_IMAGE="php:8.2-cli"

# Container shell (default: /bin/bash)
export PHP_CLI_SHELL="/bin/sh"
```

### Advanced Configuration

```bash
# Platform specification (useful for M1 Macs)
export PHP_CLI_PLATFORM="linux/amd64"

# Log file location
export PHP_CLI_LOG_FILE="$HOME/.php-cli.log"

# Additional volume mounts
export PHP_CLI_VOLUMES="-v /host/path:/container/path"

# Port mappings
export PHP_CLI_PORTS="-p 8080:80 -p 3306:3306"

# Docker network
export PHP_CLI_NETWORK="--network my-network"
```

## 💡 Examples

### Laravel Development

```bash
# Create new Laravel project
php composer create-project laravel/laravel my-app
cd my-app

# Install dependencies
php composer install

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server (if configured with ports)
php artisan serve --host=0.0.0.0
```

### Composer Package Management

```bash
# Install packages
php composer require laravel/sanctum

# Update dependencies
php composer update

# Dump autoload
php composer dump-autoload

# Show installed packages
php composer show
```

### General PHP Development

```bash
# Execute PHP scripts
php script.php

# Check syntax
php -l file.php

# Interactive PHP shell
php -a

# Run PHP built-in server
php -S 0.0.0.0:8000
```

### Container Management

```bash
# Check what's running
php status

# View recent logs
php logs --tail 50

# Enter container for debugging
php shell

# List all project containers
php list

# Clean restart
php stop && php start
```

## 🔧 How It Works

1. **Container Naming**: Each directory gets a unique container name based on a hash of the full path
2. **Auto-Start**: Containers start automatically when you run PHP commands
3. **Volume Mounting**: Current directory is mounted to `/var/www/html` in the container
4. **Persistence**: Containers persist between sessions for faster startup
5. **Isolation**: Different projects don't interfere with each other

### Container Naming Convention

```
php-cli-<8-char-hash>
```

For example, if you're in `/home/user/projects/my-app`, the container might be named `php-cli-a1b2c3d4`.

## 🐛 Troubleshooting

### Common Issues

**Docker not running:**
```bash
# Check Docker status
docker info
```

**Container won't start:**
```bash
# Check logs for errors
php logs

# Try restarting Docker
# Then restart the container
php restart
```

**Permission issues:**
```bash
# Enter shell and check permissions
php shell
ls -la /var/www/html
```

**Port conflicts:**
```bash
# Check what's using the port
lsof -i :8000

# Use different port
export PHP_CLI_PORTS="-p 8080:8000"
```

### Debug Information

```bash
# Show current configuration
php config

# Show debug info including Docker status
php debug

# List all containers
php list
```

### Clean Reset

```bash
# Stop and remove container
php stop
docker rm $(php config | grep "Generated Name" | cut -d: -f2 | xargs)

# Restart fresh
php start
```

## 🎛️ Environment Variables Reference

| Variable | Default | Description |
|----------|---------|-------------|
| `PHP_CLI_CONTAINER_NAME` | `php-cli` | Base name for containers |
| `PHP_CLI_IMAGE` | `php:8.3-cli` | Docker image to use |
| `PHP_CLI_SHELL` | `/bin/bash` | Shell for interactive sessions |
| `PHP_CLI_PLATFORM` | (auto) | Platform specification |
| `PHP_CLI_LOG_FILE` | `~/.local/bin/php.log` | Log file location |
| `PHP_CLI_VOLUMES` | (none) | Additional volume mounts |
| `PHP_CLI_PORTS` | (none) | Port mappings |
| `PHP_CLI_NETWORK` | (default) | Docker network |

## 🔄 Integration with IDEs

### VS Code

Add to your workspace settings:
```json
{
    "php.executablePath": "/path/to/php",
    "php.validate.executablePath": "/path/to/php"
}
```

### PhpStorm

Configure PHP interpreter to use the script path for remote PHP execution.

## 📈 Performance Tips

1. **Keep containers running**: Don't stop containers frequently
2. **Use appropriate images**: Choose the smallest image that meets your needs
3. **Optimize volumes**: Only mount necessary directories
4. **Network optimization**: Use host networking for development if possible

## 📍 File Locations

- **Script location**: `~/.local/bin/php`
- **Log file**: `~/.local/bin/php.log` (configurable via `PHP_CLI_LOG_FILE`)
- **Container naming**: `php-cli-<8-char-hash>` based on current directory
- **Working directory**: Current directory mounted to `/var/www/html` in container

## 🔗 Integration Tips

### Shell Aliases
Create convenient aliases for common tasks:

```bash
# Add to your shell profile
alias pa="php artisan"
alias pc="php composer"
alias psh="php shell"
alias pst="php status"
```

### IDE Integration
Configure your IDE to use the containerized PHP:

- **VS Code**: Set `php.executablePath` to the script location
- **PhpStorm**: Configure remote PHP interpreter pointing to the script
- **Vim/Neovim**: Use the script path in PHP-related plugins

---

**💡 Pro Tip**: Use `php config` to verify your setup and `php debug` when things go wrong!
