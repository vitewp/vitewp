### Initialization

Here are the steps that should be performed when initializing a new WordPress project.

1.  Clone the starter repository: `git clone git@github.com:vitewp/vitewp.git example.com`
2.  Prepare the local environment:
    1.  Create local server with PHP 8.2 and MySQL 8.4.
    2.  Create a new database for the project.
    3.  Assign a new domain to the project root with SSL: `https://example.test`
3.  In the terminal, go to the project root and run:
    1.  `./wp.sh wp:env` and set the environment details in newly created `.env` file.
    2.  `./wp.sh wp:config` and set the environment details in `wp-config-db.php` file.
    3.  `./wp.sh wp:init` to install WordPress.

📹 https://www.youtube.com/watch?v=USqQ8lZuIsc

### Installation

Here are the steps that should be performed when initializing an ongoing WordPress project.

1.  Clone the project repository: `git clone git@bitbucket.org:coditive/example.com.git`
2.  Prepare the local environment:
    1.  Create local server with PHP 8.2 and MySQL 8.4.
    2.  Create a new database for the project.
    3.  Assign a new domain to the project root with SSL: `https://example.test`
3.  Open the terminal, go to the project root and run:
    1.  `./wp.sh wp:env` and set the environment details in newly created `.env` file.
    2.  `./wp.sh wp:config` and set the environment details in `wp-config-db.php` file.
4.  Open terminal, go to `wp-content/themes/example` directory and run:
    1.  `composer install`
    2.  `yarn`
    3.  `yarn build`
5.  Get the `db.sql.gz` file from the team and put it in the project root directory.
6.  Open the terminal and in the project root fire the `./wp.sh db:import` command.
7.  Download the `wp-content/uploads` to your local environment from the production server.
8.  Open the `https://example.test`.

### Development

The project uses [Vite](https://pragmate.dev/wordpress/vite/integration/) as a bundler and development server with [HMR and Hot Reload](https://pragmate.dev/wordpress/vite/integration/#_4-bundling-improves-development-experience) features.

*   `yarn dev` - Starts the development server \[[🔗](https://pragmate.dev/wordpress/vite/integration/#_4-bundling-improves-development-experience)\].
*   `yarn build` - Builds theme assets in production mode \[[🔗](https://pragmate.dev/wordpress/vite/integration/#_2-bundling-improves-application-performance)\] \[[🔗](https://pragmate.dev/wordpress/vite/integration/#_3-bundling-improves-old-browsers-support)\].
*   `yarn release `\- Builds theme in production mode and put files to put on server in the `.output` directory.
*   `yarn format` - Formats codebase to meet defined coding standards.
*   `yarn lint` - Checks codebase for meeting defined coding standards.

### General

*   The project uses [Object Oriented](https://pragmate.dev/wordpress/architecture/oop-vs-procedural/#object-oriented-approach-in-wordpress) approach.
*   The project uses [Singleton](https://pragmate.dev/wordpress/architecture/singleton/) for managing modules.
*   The project uses [DocHooks syntax](https://pragmate.dev/wordpress/dochooks/#what-are-dochooks) for handling filters and actions.
*   The project uses [Laravel Blade](https://pragmate.dev/wordpress/blade/introduction/) for creating components HTML structure.
*   The project uses [Ignition](https://flareapp.io/docs/ignition/introducing-ignition/overview) extension to track errors.
*   The project defines [coding standards](https://pragmate.dev/environment/linting/) for [PHP](https://pragmate.dev/php/phpcs/), [SCSS](https://pragmate.dev/css/stylelint/), [JS](https://pragmate.dev/js/eslint/) that must be met.
*   The project uses Gutenberg to build the visual layer with [ACF Blocks](https://www.advancedcustomfields.com/resources/blocks/).
*   The project [should limit plugins usage](https://pragmate.dev/wordpress/do-you-need-plugins/#how-to-decide-about-plugins-usage) as much as possible.
*   The project should [split the responsibilities](https://pragmate.dev/architecture/model-view-controller/) as much as possible.
*   The project uses [GitFlow](https://danielkummer.github.io/git-flow-cheatsheet/) as a branching workflow.
*   Server details, passwords and other sensitive data are stored in 1Password.
*   During the development phase all the custom blocks should be listed on the demo template so the team can access them at one place.

### Code Review

*   Each `feature` branch must be merged to `develop` branch only via the Pull Request.
*   Each Pull Request must meet the following requirements before merging:
    *   It must pass the coding standard checks defined in Github Actions / Bitbucket Pipelines.
    *   It should include Toggl, Basecamp, Preview links in the description.
    *   It should include screenshots from implementation.
    *   It should include screenshot with Performance and Accessibility scores.
        *   [Lighthouse](https://developer.chrome.com/docs/lighthouse/overview) built-in into Chrome can be used for measurements.
        *   There should be not not any accessibility issues related to the specific feature.
        *   There should be not not any performance issues related to the specific feature.
    *   It should have at least one approval from the project leader.
        *   Pull requests must be assigned for review only when all the previous requirements are met.

## Components

The project is created using a [component-driven](https://pragmate.dev/architecture/component-driven-development/#what-are-the-5-rules-of-a-good-component) approach. It consists of [components](https://github.com/vitewp/vitewp/tree/master/wp-content/themes/footmate/resources/components), [blocks](https://github.com/vitewp/vitewp/tree/master/wp-content/themes/footmate/resources/blocks), and [templates](https://github.com/vitewp/vitewp/tree/master/wp-content/themes/footmate/resources/templates). Although each of them serve different role they share a similar style.

### Component

Components are the smallest units used to build websites supported by [Laravel Blade](https://laravel.com/docs/12.x/blade#rendering-components). They are available throughout the website and should include only the most basic functionalities to maintain simplicity.

*   Creation: `yarn component --id=button --title=Button`
*   Usage: `<x-button />`

### Block

Blocks are larger units integrated with [ACF and Gutenberg](https://www.advancedcustomfields.com/resources/blocks/), used to define the layout of the website. They can include more complex functionalities and utilize granular components. When possible, blocks should use [InnerBlocks](https://www.advancedcustomfields.com/resources/acf-blocks-using-innerblocks-and-parent-child-relationships/) to provide flexibility for clients and should [utilize container queries](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_containment/Container_queries) instead of traditional media queries.

*   Creation: `yarn component --id=button --title=Button`
*   Usage: `{!! block('base')->render(['title' => 'custom title']) !!}`

### Template

Templates are the largest components. Those defined in the theme are automatically resolved and rendered by WordPress.

*   Creation: `yarn template --id=article --title=Article`
*   Usage: Choose page template from WordPress admin panel.

### Example

1.  Block Structure:
    1.  [Controller](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/app/Blocks/Base.php): Used for defining the [block schema](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/app/Blocks/Base.php#L13-L17), [default data](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/app/Blocks/Base.php#L18-L22) and any backend mechanisms.
    2.  [Template](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/resources/blocks/base/template.blade.php): Used for displaying [the data](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/resources/blocks/base/template.blade.php#L5) that has been [defined in the block](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/app/Blocks/Base.php#L13-L17).
    3.  [Scripts](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/resources/blocks/base/script.js): Used for defining user interactivity.
    4.  [Styles](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/resources/blocks/base/style.scss): Used for defining visual layer.
    5.  [ACF Fields](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/resources/fields/group_67a362c847851.json): Block fields saved with [Local JSON](https://www.advancedcustomfields.com/resources/local-json/) implementing [block schema](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/app/Blocks/Base.php#L13-L17) for Gutenberg.
2.  Block Creation
    1.  Use the `yarn block --id=button --title=Button` command in the theme's root. The command will create a new block and automatically load it into the system. You might need to restart your development server.
    2.  Define the [block schema](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/app/Blocks/Base.php#L13-L17) and fill it with the [default values](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/app/Blocks/Base.php#L18-L22) when needed. Block schema uses the [Laravel Validation](https://laravel.com/docs/10.x/validation#available-validation-rules) component, but if you're not familiar with this yet, please at least define the simplest typing schema to check if the passed is a [string](https://laravel.com/docs/10.x/validation#rule-string), [array](https://laravel.com/docs/10.x/validation#rule-array), [boolean](https://laravel.com/docs/10.x/validation#rule-boolean), or [required](https://laravel.com/docs/10.x/validation#rule-required).
    3.  Go to the ACF Field Groups, clone the `Block: Base` group, and adjust to the newly created block while keeping the same naming conventions. The field naming and validation rules should reflect the ones defined in the schema.
3.  Block Rendering
    1.  Use the `{!! block('base')->render(['title' => 'custom title']) !!}` function in Blade templates. To fill the block with values, pass the data array to the `render` function based on the [defined schema](https://github.com/vitewp/vitewp/blob/master/wp-content/themes/footmate/app/Blocks/Base.php#L13-L17).
    2.  Choose the block in the Gutenberg and fill with the data.