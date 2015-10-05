# SEO Plugin for Craft

Makes it easy to set site-wide and per-entry SEO details within [Craft CMS](http://buildwithcraft.com)

## What it does

- Set a site-wide fallback `<title>`
- Set a site-wide fallback `<meta name="description">`
- Allows you to set custom title, description, and open graph images for each entry

## Installation

To install, follow these steps:

1. Upload the `odcseo` folder to your `craft/plugins/` folder.
2. Go to _Settings > Plugins_ from your Craft control panel and enable the _SEO_ plugin.
3. Open the settings for the plugin and customize your settings
4. The plugin automatically adds an _SEO_ field group to Craft, but you need to manually add this field group to every section that needs customized SEO details.
5. Go through each of your entries and optionally customize the title, description, and image.

## Templating

To add the necessary title and meta information to your pages, add the following to the `<head>` in each of your craft layout templates:

```
{{ craft.odcSeo.meta }}
```
