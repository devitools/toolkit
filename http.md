# 🌐 HTTP CLI Tool

A powerful command-line HTTP client that enhances `curl` with colorized output, JSON formatting, and simplified syntax for API testing and development.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Quick Start](#quick-start)
- [Usage](#usage)
- [Configuration](#configuration)
- [Examples](#examples)
- [Requirements](#requirements)

## 🎯 Overview

The `http` script is a command-line utility designed to simplify and enhance the usage of `curl` for making HTTP requests. It provides a user-friendly interface, colorized output, and additional features to streamline common tasks such as sending requests, viewing responses, and handling authentication.

## ✨ Features

- **🚀 Simplified HTTP Requests:**
  - Supports various HTTP methods (GET, POST, PUT, DELETE, etc.) with easy syntax.
  - Automatically formats and sends requests using `curl` under the hood.

- **🎨 Colorized Output:**
  - Response headers and status codes are displayed with custom colors for better readability.
  - Success, failure, and error status codes are highlighted.

- **💅 Response Beautification:**
  - The response body is processed and beautified for easier inspection, especially for JSON content.
  - Automatic JSON syntax highlighting with colored keys, values, and symbols.

- **🔍 Verbose Mode:**
  - Optionally displays detailed response headers and additional information for debugging.

- **🔐 Authentication & Configuration:**
  - Includes commands for configuring authentication and login, making it easier to work with APIs that require credentials.
  - Support for headers management and persistent configuration.

## 🚀 Installation

### Prerequisites
- `curl` must be installed and available in your system's PATH
- Compatible with macOS and most Unix-like systems

### Installation Steps

1. **Download and make executable:**
   ```bash
   chmod +x http
   ```

2. **Add to PATH for global access:**
   
   **Option A: Copy to a directory already in PATH**
   ```bash
   sudo cp http /usr/local/bin/
   ```
   
   **Option B: Add ~/.local/bin to your PATH (Recommended)**
   ```bash
   # Add to your shell profile (~/.bashrc, ~/.zshrc, etc.)
   echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc
   source ~/.bashrc
   ```
   
   **Option C: Create symbolic link**
   ```bash
   sudo ln -s $(pwd)/http /usr/local/bin/http
   ```

3. **Verify installation:**
   ```bash
   http --help
   ```

### PATH Configuration

To make the `http` tool available globally in your terminal, ensure `~/.local/bin` is in your PATH:

```bash
# For Bash users
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc && source ~/.bashrc

# For Zsh users  
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.zshrc && source ~/.zshrc

# For Fish users
fish_add_path ~/.local/bin
```

## ⚡ Quick Start

```bash
# Make a GET request
http get https://api.github.com/users/octocat

# POST with JSON data
http post https://httpbin.org/post name=John age=30

# Add custom headers
http get https://api.example.com/data Authorization:"Bearer token123"

# Enable verbose mode for debugging
HTTP_VERBOSE=1 http get https://api.example.com/status
```

## 📚 Usage

The script is invoked from the command line with flexible syntax:

```bash
http [method] [url] [data/options]
```

### HTTP Methods

| Method | Description | Example |
|--------|-------------|---------|
| `GET` | Retrieve data | `http get https://api.example.com/users` |
| `POST` | Send data | `http post https://api.example.com/users name=John` |
| `PUT` | Update resource | `http put https://api.example.com/users/1 name=Jane` |
| `DELETE` | Delete resource | `http delete https://api.example.com/users/1` |
| `PATCH` | Partial update | `http patch https://api.example.com/users/1 email=new@email.com` |

### Configuration Commands

| Command | Description | Example |
|---------|-------------|---------|
| `configure` | Set up authentication or configuration | `http configure` |
| `login` | Authenticate with a service | `http login` |
| `set-header` | Add persistent header | `http set-header Authorization "Bearer token"` |
| `remove-header` | Remove header | `http remove-header Authorization` |

## ⚙️ Configuration

The tool supports persistent configuration through `.http.conf` files:

### Environment Variables

```bash
# Enable verbose mode globally
export HTTP_VERBOSE=1

# Set default timeout
export HTTP_TIMEOUT=30
```

### Configuration File

Create `.http.conf` in your project directory:

```bash
# Base URL for requests
host=https://api.example.com

# Default headers
--header "Content-Type: application/json"
--header "Authorization: Bearer your-token-here"

# Other curl options
--timeout 30
--max-time 60
```

## 💡 Examples

### API Testing

```bash
# Test API endpoint
http get https://jsonplaceholder.typicode.com/posts/1

# Create new post
http post https://jsonplaceholder.typicode.com/posts \
  title="My Post" body="Post content" userId=1

# Update existing post
http put https://jsonplaceholder.typicode.com/posts/1 \
  title="Updated Post" body="Updated content"
```

### Authentication

```bash
# Bearer token authentication
http get https://api.github.com/user \
  Authorization:"Bearer ghp_your_token_here"

# Basic authentication
http get https://httpbin.org/basic-auth/user/pass \
  --user user:pass

# API key in header
http get https://api.example.com/data \
  X-API-Key:"your-api-key"
```

### File Uploads

```bash
# Upload file
http post https://httpbin.org/post \
  --form file=@document.pdf

# Upload with additional data
http post https://api.example.com/upload \
  --form file=@image.jpg \
  --form description="Profile picture"
```

### Response Handling

```bash
# Save response to file
http get https://api.example.com/data > response.json

# Show only headers
http head https://api.example.com/status

# Follow redirects
http get https://bit.ly/short-url --location
```

## 🔧 Advanced Features

### Verbose Mode
Enable detailed output including request headers and timing:

```bash
HTTP_VERBOSE=1 http get https://api.example.com/data
```

### JSON Formatting
Responses are automatically formatted and colorized for JSON content.

### Status Code Highlighting
- 🟢 Success (2xx): Green background
- 🟡 Redirection (3xx): Yellow background  
- 🔴 Client/Server Error (4xx/5xx): Red background

## 🐛 Troubleshooting

### Common Issues

**Command not found:**
```bash
# Check if http is in PATH
which http

# Add to PATH if needed
export PATH="$HOME/.local/bin:$PATH"
```

**curl not found:**
```bash
# Install curl (macOS)
brew install curl

# Install curl (Ubuntu/Debian)
sudo apt-get install curl
```

**Permission denied:**
```bash
# Make script executable
chmod +x ~/.local/bin/http
```

## 📍 File Location

- Script location: `~/.local/bin/http`
- Configuration: `.http.conf` (project-specific) or `~/.curlrc` (global)
- Log files: Stored in current directory when verbose mode is enabled

---

**💡 Pro Tip**: Use `http configure` to set up persistent authentication and `HTTP_VERBOSE=1` for debugging API issues!
