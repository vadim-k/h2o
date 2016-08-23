ABOUT

A stand alone javascript library for viewing images on the full screen.
Using the touch/mouse position for panning.

All styling of image elements is up to the user, Intense.js only handles the
creation, styling and management of the image viewer and captions.



INSTALLATION
Install the module as usual, more info can be found on:
http://drupal.org/documentation/install/modules-themes/modules-7


USAGE / CONFIGURATION

- Enable this module and its dependency, core image module.

- At admin/config/people/accounts/fields, Content types or any fieldable entity,
  -- click "Manage display".

- Under "Format", choose "Intense images" for image field, and click the
  "Configure" icon.
  If Slick or Slick media modules are installed, additional integration is
  available under Slick media and Slick carousel formatters (D8 only by now).


OPTIONAL INTEGRATION
D8 only by now.
[1] http://dgo.to/slick
[1] http://dgo.to/slick_media



REQUIREMENTS
- Intense library:
  o Download Intense archive from https://github.com/tholman/intense-images/
  o Extract it as is, rename "intense-images-master" to "intense", so the assets
    are available at:

    /libraries/intense/intense.min.js



AUTHOR/MAINTAINER/CREDITS
gausarts


READ MORE
See the project page on drupal.org: http://drupal.org/project/intense.

See the Intense images docs at:
- https://github.com/tholman/intense-images/
- http://tholman.com/intense-images
