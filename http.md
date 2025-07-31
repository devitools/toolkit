# http Utility Script Documentation

## Overview

The `http` script is a command-line utility designed to simplify and enhance the usage of `curl` for making HTTP requests. It provides a user-friendly interface, colorized output, and additional features to streamline common tasks such as sending requests, viewing responses, and handling authentication.

## Key Features

- **Simplified HTTP Requests:**
  - Supports various HTTP methods (GET, POST, PUT, DELETE, etc.) with easy syntax.
  - Automatically formats and sends requests using `curl` under the hood.

- **Colorized Output:**
  - Response headers and status codes are displayed with custom colors for better readability.
  - Success, failure, and error status codes are highlighted.

- **Response Beautification:**
  - The response body is processed and beautified for easier inspection, especially for JSON content.

- **Verbose Mode:**
  - Optionally displays detailed response headers and additional information for debugging.

- **Authentication & Configuration:**
  - Includes commands for configuring authentication and login, making it easier to work with APIs that require credentials.

## Usage

The script is invoked from the command line, accepting subcommands and options:

```
http [method] [url] [options]
```

### Example

```
http GET https://api.example.com/resource
```

### Subcommands
- `configure`: Set up authentication or other configuration options.
- `login`: Authenticate with a service or API.

## Response Handling

- The script parses the response from `curl`, separating headers and body.
- Headers are colorized and shown if verbose mode is enabled.
- The body is cleaned of empty lines and beautified for display.
- The HTTP status code is extracted and shown with appropriate coloring.

## Customization

- Colors and formatting can be adjusted by modifying the script variables.
- Additional methods and options can be added as needed.

## Requirements

- `curl` must be installed and available in your system's PATH.
- The script is compatible with macOS and most Unix-like systems.

## File Location

- The script is located at: `~/.local/bin/http`

---

This utility is ideal for developers and power users who frequently interact with HTTP APIs and want a more convenient and visually appealing command-line experience.
