# UniDataParser

UniDataParser is a versatile log parsing tool designed to search and extract information, such as URLs, usernames, and passwords, from log files. It provides a user-friendly web interface for efficient log analysis.

## Features

- **Search Functionality:** Quickly search for URLs and associated login credentials within log files.
- **Web Interface:** User-friendly web interface for easy interaction.
- **Flexible Parsing:** Parses log files with various formats, recognizing URLs, usernames, and passwords.

## Usage

1. **Requirements:**
   - Web server with PHP support.
   - Log files structured with URLs, usernames, and passwords.

2. **Installation:**
   - Clone the repository to your web server directory.

    ```bash
    git clone https://github.com/ixzy/UniDataParser.git
    ```

3. **Configuration:**
   - Customize the log file structure and formatting in the PHP script as needed.


4. **Results:**
   - View the search results, including URLs, usernames, passwords, and folder links.

## File Structure

- `index.php`: Main web interface for searching log files.
- `parser.php`: PHP script handling log file parsing and search functionality.
- `styles.css`: Stylesheet for the web interface.
- `typed.js`: JavaScript library for animated text (used for displaying total records).
- `/api/index.php`: Api returns logfile important autofill

## Contributing

Feel free to contribute, report issues, or suggest improvements! Check out the [Contributing Guidelines](CONTRIBUTING.md).


## Acknowledgments

- [Typed.js](https://github.com/mattboldt/typed.js/) - A JavaScript library for animated text.
- [Bootstrap](https://getbootstrap.com/) - Used for basic styling and responsive design.

Happy parsing!
