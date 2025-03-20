# Sage HTML Forms submissions

This package can be use in combination with [HTML Forms plugin](https://wordpress.org/plugins/html-forms/).
You can display form submissions anywhere you want. One of the use-cases is a petition.

## Installation

You can install this package with Composer:

```bash
composer require tombroucke/sage-html-forms-submissions
```

## Shortcodes

### Show list of submissions

```
[sage_html_forms_submissions slug="petition" fields="name:Name"]
```

| Attribute | Description                                                             |
| --------- | ----------------------------------------------------------------------- |
| slug      | The form slug for which you want to display submissions (\*)            |
| id        | The form id for which you want to display submissions (\*)              |
| fields    | Comma-separated list of fields you want to show. field_key:Label (\*\*) |
| sort      | 'asc' or 'desc' (default)                                               |

(\*) Only one is needed
(\*\*) You can add indexes / numbering with index:#. E.g. index:#,name:Name

### Show submission count

```
[sage_html_forms_submissions_count slug="petition"]
```

| Attribute | Description                                                  |
| --------- | ------------------------------------------------------------ |
| slug      | The form slug for which you want to display submissions (\*) |
| id        | The form id for which you want to display submissions (\*)   |

## Extra

### Add a checkbox with a name of `anonymous` to anonymize submissions

```html
<div class="form-check mb-2">
  <input
    class="form-check-input"
    type="checkbox"
    name="anonymous"
    value="true"
    id="anonymous"
  />
  <label class="form-check-label" for="anonymous">
    I\'d rather not have my name published
  </label>
</div>
```
