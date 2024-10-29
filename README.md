# PasswordSync

**PasswordSync** is a custom Nextcloud app that automatically synchronizes user password changes to a Linux server, ensuring consistency across platforms. When a user updates their password in Nextcloud, this app securely sends the new password to a designated Linux server, where it is updated for the corresponding Linux user account.

## Features

- **Automatic Password Sync**: Updates the Linux server’s user password whenever a user changes their password in Nextcloud.
- **Secure Communication**: Sends password changes over HTTPS to the Linux server.
- **Customizable Endpoint**: Easily configure the Linux server endpoint URL for password updates.

## Requirements

- **Nextcloud 21+** for compatibility with event-driven architecture.
- **App Password** enabled in Nextcloud for secure API communication.
- **Linux Server** with an accessible HTTPS endpoint to receive password update requests.

## Installation

1. **Clone the Repository**: Place the `passwordsync` directory inside Nextcloud’s `apps` directory.
   ```bash
   cd /var/www/nextcloud/apps
   git clone https://github.com/yourusername/passwordsync.git

