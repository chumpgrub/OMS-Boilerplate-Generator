# OMS Boilerplate Plugin Generator

## How To Use
- To use Locally:
  - Pull down to your local machine
  - Use your favorite CLI, e.g., terminal, iTerm, whatever it's call on Windows, and `cd` 
  into the root directory. 
  - Start up a simple PHP server using `php -S localhost:8888`. 
    - Note: you can use any port you wish, but if you do this, you will need to update the URL in `src/App.js` 
    around line 151/159 - the `url` , `proxy: port` and `this.setState`.
    - Documentation for simple server: https://www.php.net/manual/en/features.commandline.webserver.php
  - Next, `cd` into the `api` directory. 
  - Use `npm start` or `yarn start` to start watching the folder. This will
  start up a development server on `https://localhost:3000`
  - Your browser will open a tab to the OMS Plugin Generator. 
  - Profit!


- To use on the internet:
  - Don't.

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
