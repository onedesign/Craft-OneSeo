# SEO Plugin for Craft

Makes it easy to set site-wide and per-entry SEO details within [Craft CMS](http://buildwithcraft.com)

## What it does

- Set a site-wide fallback `<title>`
- Set a site-wide fallback `<meta name="description">`
- Allows you to set custom title, description, and open graph images for each entry

## Installation

To install via Composer: 

1. Add a `composer.json` file to your project root
2. Edit your `composer.json` file to include the following
```
{
    "require": {
        "onedesign/oneseo": "x.x.x"
    }
}
```
3. Run `composer install`

To install without composer:

1. Download the latest release.
2. Rename the uncompressed folder to `oneseo`
3. Place that folder into your `craft/plugins` folder


## Setup
1. Backup your database (just in case).
2. Go to _Settings > Plugins_ from your Craft control panel and enable the _SEO_ plugin.
3. Upon installation, the plugin automatically adds an _SEO_ field group to Craft and adds this field group to every entry type with a url.
4. Open the settings for the plugin and customize your settings
5. Go through each of your entries and optionally customize the title, description, and image.

## Templating

To add the necessary title and meta information to your pages, add the following to the `<head>` in each of your craft layout templates:

```
{% hook "getSeoMeta" %}
{{ craft.oneSeo.meta }}
```

### Customizing meta details per page

From within any page template, you can customize the output of `{{ craft.oneSeo.meta }}` just by setting a few variables: `customMetaTitle`, `customMetaDescription` and `customMetaImage`.

```twig
{% set customMetaTitle = 'My Custom Title' %}
{% set customMetaDescription = 'My very special meta' %}
{% set customMetaImage = entry.featuredImageField.first.url %}
```

For the `customMetaTitle` you can also use an array of strings, which will be combined with the "Title Divider Character" set in the CMS:

```twig
{% set customMetaTitle = ['My Custom Title', 'Some Section'] %}
{# Results in 'My Custom Title – Some Section – Site Name' #}
```
