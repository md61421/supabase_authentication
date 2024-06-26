# Supabase Authentication

Supabase Authentication is a custom Drupal 10 module that integrates Supabase with Drupal for user authentication.

## Features

- Allows administrators to configure Project URL and API Key.
- Automatically creates a user in Supabase when a new Drupal user is created.
- Authenticates the user with Supabase and retrieves the access token.

## Requirements

- Drupal 10
- Composer
- Supabase account

## Installation

1. **Clone the module**:
    ```sh
    cd /path/to/drupal/web/modules/custom
    git clone https://github.com/md61421/supabase_authentication.git
    ```

2. **Install dependencies**:
    ```sh
    cd supabase_authentication
    composer install
    ```

3. **Enable the module**:
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
    - Go to the `Settings` tab in your Supabase project dashboard.
    - Select `API` from the sidebar menu.
    - The `Project URL` and `API Key` will be listed under the `Config` section.

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
```

Feel free to adjust any sections to better match your project's specifics or personal preferences.