# Shortcode for get ACF subfield
This snippet add a shortcode for get ACF subfield value.

## How to use
1. Add the snippet to your theme's functions.php file or to a custom plugin.
2. Use the shortcode `[get_acf_subfield]` in your posts, pages or custom post types.

### Parameters
- `post_id`: (int) {Optional} Post ID, default is current post ID.
- `field`: (string) {Required} ACF field key.
- `path`: (string) {Required} ACF subfield path, use "->" to separate levels.
- `cache`: (bool) {Optional} Use cache, default is true.

### Examples
- Get subfield value in a repeater from a specific post.
```php
[get_acf_subfield post_id="123" field="my_repeater_field_key" path="0->my_sub_key" cache="true"]
```

- Get subfield value in a group field from current post.
```php
[get_acf_subfield field="my_group_field_key" path="my_sub_key"]
```

- Get JSON from group or repeater field.
```php
[get_acf_subfield field="my_group_field_key"]
```

### Return
- ACF subfield value.
- If is an array, return a json encoded array, for easy use in javascript, or debug the path.

## Notes
- This snippet requires Advanced Custom Fields (ACF) plugin.
- This snippet is use `get_field()` function for get ACF field value.
- This shortcode works for group, repeater and flexible content fields.