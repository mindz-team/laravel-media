# Changelog

## Version 1.0.11

### Breaking Changes
* The `spatie/image` package has introduced a significant change. The `Spatie\Image\Manipulations::FIT_CONTAIN` and other similar constants are no longer supported. This was a necessary modification to enhance the functionality and extendibility of the aforementioned package.

### Migration Steps
* Wherever the constants like `Spatie\Image\Manipulations::FIT_CONTAIN` were used previously, they must now be replaced with corresponding Enums. For example, `Spatie\Image\Manipulations::FIT_CONTAIN` should be replaced with `Spatie\Image\Enums\Fit::Contain`. The working of these Enums matches exactly to how the respective constants worked, just that they are now structured better for code maintainability.

Please note: These changes are crucial for the system to function correctly with the `spatie/image` package in the version 1.0.11. Failing to adapt to these changes might cause your code to break. We appreciate your understanding and cooperation in this matter.