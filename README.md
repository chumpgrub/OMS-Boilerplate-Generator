# OMS Boilerplate Plugin Generator

## Updates

### 2/6/23 - v2.1.0 
- Update: Adds methods to register and add default Gutenberg blocks
- Update: Updates index.php to create the template for the block.json in blocks/block.json
- Update: Updates index.php to create the block render template in the templates directory
- Housekeeping: Minor updates to code to appease the CS gods.
- Tag: Adds tag!

### 12/13/22 - 2.0.0
Major update. Changing version to 2.0.0.

- Update: Removes oms specific hooks and markup from templates.
- Update: Function updated for PSR standards, e.g., function return type declarations, parameter types.
- Update: Remove support/output for WooSidebars
- Update: Remove support/output for `page_title`
- Update: Remove support/output for `header_image`

### 07/16/21
- Removed Google Analytics
- Cleaned cruft
- Added some notes

### 12/14/20
- Post Type code preview missing quote typo fixed.
- Prepend taxonomy slug if "Category" to prevent issues with existing the existing Blog Category taxonomy.
- Adds conditional "ACF Required" check.
- Adds conditional WooSidebars support check.
- Refactored ACF sub page registration for consistent code style.
