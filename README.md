# Fooz task

Implementation of required tasks on top of the Twenty Twenty-Five block theme.

## Scope
- Child theme for styling and templates
- Custom plugins for book post type and gutenberg blocks

## Features

**Books (plugin: fooz-library)**
- Custom post type: Book (`library`)
- Taxonomy: Genre (`book-genre`)
- Labels prepared for translation

**Templates (child theme)**
- `single-book.html` - single book view
- `taxonomy-genre.html` - genre archive with pagination
- Latest Books list on single book loaded via AJAX (JSON)

**FAQ Block (plugin: fooz-faq)**
- Custom Gutenberg block: FAQ Accordion
- InnerBlocks for multiple Q&A items
- Accordion based on `<details>/<summary>`
- Numeric order via CSS counters

## Setup
1. Copy plugins into `wp-content/plugins`
2. Activate child theme: `fooz`
3. Activate plugins:
   - Fooz Library
   - Fooz FAQ

## Notes
- Tailwind used for styling
