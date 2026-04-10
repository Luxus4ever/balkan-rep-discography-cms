# Balkan Rep Discography CMS

## Overview

Balkan Rep Discography CMS is a custom-built PHP/MySQL web application for managing and presenting album discography data for Balkan rap artists.

The system includes:

* Public frontend for browsing albums and artists
* Admin panel for content management
* User system (registration, login, favorites)
* Chat system with file upload support
* Logging and activity tracking system

---

## Live Demo

A fully functional version of the project is available online:

https://diskografija.org

---

## Features

### Core

* Album & artist management
* Song database
* Streaming links integration
* Favorites system

### Admin Panel

* Manage albums, artists, users
* Role-based access (admin/moderator)
* Activity logging

### Chat System

* User-to-user messaging
* File/image upload
* Seen/read status tracking

### Security

* CSRF protection
* Password hashing
* Token-based password reset

---

## Tech Stack

* PHP (OOP + procedural)
* MySQL
* Bootstrap
* JavaScript / jQuery

---

## Installation

This repository is **not intended for full local installation**, as sensitive data is intentionally excluded.

To run the project locally, you would need to:

1. Create and configure your own MySQL database (structure not included)
2. Update configuration in:

```
config/config.example.php
```

3. Run server (WAMP / Apache)

---

## Notes

- Database dumps are intentionally excluded
- Uploaded media (images, files) are not included
- Configuration files contain example values only
- Some features may be limited in this version

---

## Author

Darko Gavrić
