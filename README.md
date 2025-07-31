# 🧰 Toolkit

A collection of self-contained and useful bash utilities for daily development tasks.

## 📋 Table of Contents

- [Overview](#overview)
- [Available Tools](#available-tools)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)

## 🎯 Overview

**Toolkit** is a collection of bash scripts that abstract common development tools, making them easier and more intuitive to use. Each tool is self-contained and can be used independently.

## 🛠️ Available Tools

### 🌐 HTTP
A powerful abstraction over curl to facilitate HTTP requests with advanced features.

**Features:**
- ✅ Complete HTTP methods (GET, POST, PUT, PATCH, DELETE)
- ✅ Automatic JWT token authentication
- ✅ Persistent header management
- ✅ Automatic JSON colorization
- ✅ Per-project configuration
- ✅ Integrated login/logout system

[📖 Complete HTTP documentation](http.md)

### 🐘 PHP
A Docker abstraction to easily use PHP through containers, with per-project isolation.

**Features:**
- ✅ PHP execution without local installation
- ✅ Automatic per-project isolation (based on directory)
- ✅ Complete container management
- ✅ Support for Composer, Artisan and other commands
- ✅ Flexible configuration via environment variables
- ✅ Detailed logging

[📖 Complete PHP documentation](php.md)

## 🚀 Installation

1. Clone this repository:
```bash
git clone <repository-url>
cd toolkit
```

2. Make the scripts executable:
```bash
chmod +x http php
```

3. Add to your PATH (optional):
```bash
# Add to your ~/.bashrc or ~/.zshrc
export PATH="$PATH:/path/to/toolkit"
```

Or copy the scripts to a directory already in PATH:
```bash
cp http php ~/.local/bin/
```

## 💡 Usage

### Quick Examples

**HTTP:**
```bash
# Configure base host
http setup https://api.example.com

# Login
http login --email user@example.com --password password123

# Simple requests
http get /users
http post /users '{"name":"John","email":"john@example.com"}'
```

**PHP:**
```bash
# Execute PHP directly
php -v

# Use Composer
php composer install

# Execute Artisan (Laravel)
php artisan migrate

# Enter container shell
php shell
```

## 🏗️ Project Structure

```
toolkit/
├── README.md          # This file
├── http              # HTTP/curl utility
├── http.md           # HTTP documentation
├── php               # PHP/Docker utility
├── php.md            # PHP documentation
```

## 🎨 Features

- **🔧 Self-contained**: Each tool works independently
- **🎯 Specific**: Focused on solving specific problems
- **⚡ Fast**: Efficient execution and quick startup
- **🖥️ Cross-platform**: Works on macOS and Linux
- **📝 Well documented**: Each tool has detailed documentation
- **🔍 Debug-friendly**: Clear logs and error messages

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the project
2. Create a feature branch (`git checkout -b feature/new-tool`)
3. Commit your changes (`git commit -m 'Add new tool'`)
4. Push to the branch (`git push origin feature/new-tool`)
5. Open a Pull Request

### 📋 Guidelines for New Tools

- Must be self-contained (single-file)
- Include internal help/usage
- Follow color and output patterns
- Include markdown documentation
- Be useful for daily development

## 📄 License

This project is under the MIT license. See the `LICENSE` file for more details.

## 📞 Support

If you encounter issues or have suggestions:

1. Check the tool-specific documentation
2. Open an issue on GitHub
3. Or contribute improvements via Pull Request

---

**🎉 Made with ❤️ to make daily development easier**
