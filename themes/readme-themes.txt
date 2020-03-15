About Themes:

Themes in this application work im a very similar way to how themes are applied in WordPress, although the internal plumbing is dissimilar.

To create a Theme, an HTML design must be split up into two files, header_inc.php and footer_inc.php, and reside in a uniquely named folder (named after the theme) inside the folder named 'themes'

The file common_inc.php includes code inside the function get_header() to point to the locations for the header & footer files.

The 'themes' folder goes inside the root of the web application (same level as images folder) and includes it's own header_inc.php and footer_inc.php files.

The themes include a path constant named THEME_PATH which is the virtual path to the theme itself.

Each theme has it's own string (slug) to identify the theme, for example 'SmallPark' which serves double duty as the string that is called out to identify the Theme desired in the config file ($config->Theme = "SmallPark";) and the name of the folder inside the 'themes' main folder.

The get_header() function can be overridden by placing a specific file into the get_header() function as a parameter:
get_header('aboutus_header_inc.php');

Placing this file name (without any other pathing) assumes the CURRENT CONTEXT, so if a theme is being used, the file referenced in get_header() is inside the current theme folder.

 

