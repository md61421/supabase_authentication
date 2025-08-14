# Supabase Authentication

Supabase Authentication is a custom Drupal 10 module that integrates Supabase with Drupal for user authentication.

## Features

- Allows administrators to configure Project URL and API Key.
- Automatically creates a user in Supabase when a new Drupal user is created.
- Authenticates the user with Supabase and retrieves the access token.

## Requirements

- Drupal 10 or earlier
- Supabase account
- `rafaelwendel/phpsupabase` PHP library
- `email_registration` Drupal module

## Installation

1. **Clone the module**:
    ```sh
    cd /path/to/drupal/web/modules/custom
    git clone https://github.com/md61421/supabase_authentication.git
    cd supabase_authentication
    ```

2. **Install the required PHP library**:
    ```sh
    composer require rafaelwendel/phpsupabase
    ```

3. **Install the `email_registration` module**:
    ```sh
    composer require 'drupal/email_registration'
    ```

4. **Enable the `email_registration` module**:
    ```sh
    drush en email_registration
    ```

5. **Enable the Supabase Authentication module**:
    ```sh
    drush en supabase_authentication
    ```

## Configuration

### Finding Your Supabase URL and API Key

1. **Log in to your Supabase account**:
    - Go to [Supabase](https://supabase.io) and log in to your account.

2. **Select your project**:
    - Navigate to the project you want to use.

3. **Find the API settings**:
    - Go to the `Project Settings` tab in your Supabase project dashboard.
    - Select `API` from the sidebar menu.
    - The `Project URL` and `API Key (service_role)` will be listed under the `Config` section.

4. **Enter the configuration in Drupal**:
    - Go to `Admin > Configuration > System > Supabase Authentication Settings` in your Drupal site.
    - Enter the `Project URL` and `API Key` from your Supabase project.
    - Save the configuration.

## Usage

Once configured, the module will:

- Automatically create a user in Supabase when a new Drupal user is created.
- Log any errors or success messages in the Drupal logs.

## Contributing

Contributions are welcome! Please open an issue or submit a pull request on the GitHub repository.

## License

This project is open source and available under the MIT License.