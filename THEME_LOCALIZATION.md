# Theme & Localization Features

## Dark/Light Theme Toggle

The application now supports a dark and light theme toggle. The theme preference is saved in localStorage and persists across sessions.

### How it works:
- Click the moon/sun icon in the header to toggle between themes
- Theme preference is saved automatically
- CSS variables adjust colors based on the selected theme

## Multi-Language Support

The application supports 5 languages:
- 🇺🇸 English (en)
- 🇪🇸 Spanish (es)
- 🇫🇷 French (fr)
- 🇩🇪 German (de)
- 🇮🇳 Hindi (hi)

### How to use translations in views:

```blade
{{ __('messages.key_name') }}
```

### Example:
```blade
<h1>{{ __('messages.welcome_title') }}</h1>
<p>{{ __('messages.welcome_subtitle') }}</p>
<button>{{ __('messages.shop_now') }}</button>
```

### Adding new translations:

1. Add the key to all language files in `resources/lang/{locale}/messages.php`
2. Use the translation in your view with `__('messages.key_name')`

### Example translation file structure:
```php
<?php
return [
    'key_name' => 'Translation text',
    'another_key' => 'Another translation',
];
```

## CSS Dark Theme Variables

The application uses CSS variables for easy theming:

### Light Theme (default):
```css
--bg-primary: #ffffff;
--bg-secondary: #f8fafc;
--text-primary: #334155;
--text-secondary: #64748b;
```

### Dark Theme:
```css
--bg-primary: #0f172a;
--bg-secondary: #1e293b;
--text-primary: #f1f5f9;
--text-secondary: #94a3b8;
```

All components automatically adjust based on the `data-theme` attribute on the `<html>` element.
