# Sage HTML Forms submissions

This package adds some functionality to the [HTML Forms plugin](https://wordpress.org/plugins/html-forms/).


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

| Attribute   | Description |
| ----------- | ----------- |
| slug        | The form slug for which you want to display submissions (*)      |
| id          | The form id for which you want to display submissions (*)        |
| fields      | Comma-separated list of fields you want to show. field_key:Label |

(*) Only one is needed

### Show submission count
```
[sage_html_forms_submissions_count slug="petition"]
```

| Attribute   | Description |
| ----------- | ----------- |
| slug        | The form slug for which you want to display submissions (*)      |
| id          | The form id for which you want to display submissions (*)        |
