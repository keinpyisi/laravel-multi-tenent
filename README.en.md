Here is the translation of your text into English:

---

-   [English](README.en.md)
-   [日本語](README.md)

# Creating Multiple Clients in Laravel

This is a Laravel environment that can manage multiple clients, similar to CodeIgniter's BaseClient.

The admin panel is written using Tailwind CSS. Models and other components use SweetAlert2, but feel free to create individual tenants such as "clients" on your own.

Here, we use Axios instead of Ajax because Ajax has weaker security.

Reference: [OWASP](https://cheatsheetseries.owasp.org/cheatsheets/AJAX_Security_Cheat_Sheet.html)

## Setup

Please [install Composer](https://getcomposer.org/download/). Any version is fine.

Please [install NodeJS v23](https://nodejs.org/en/download/package-manager). The reason is that it automatically minifies and encrypts JS files when writing.

Next, open CMD here and run:

```bash
composer install
```

```bash
npm install
```

If npm fails to install, you can update to a newer version with `npm update`.

<em><strong>If you also want to upgrade Laravel, don’t worry, just run this:</strong></em>

```bash
composer update
```

Then copy `.env.local` and create a new key here:

<em><strong>Make sure the APP_KEY in `.env` matches the server's key if you want to view data from the server.</strong></em>

```bash
php artisan key:generate
```

Next, create the database in PostgreSQL. Once created, set the following in `.env`:

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_created_db_name
DB_USERNAME=db_username
DB_PASSWORD=db_password
```

Once done, create the base schema.

Run the following in CMD:

This will create a base_client schema where admin panel data is stored.

```bash
php artisan tenant:create-base-schema --seed
```

You can find the admin panel username and password here. Feel free to change them.

```bash
database\seeders\AdminSeeder.php
```

Now, let's create the base client schema:

```bash
php artisan setup:base-client --seed
```

This will create the client’s base database.

For local development, open two CMD windows and run these commands:

```bash
php artisan serve
```

```bash
npm run dev
```

These will keep running while you develop.

## Preparing for Release

When releasing, always run this and upload everything in `/public/build/`:

```bash
npm run build
```

## Development Instructions

Follow these steps during development to avoid errors.

This will create a new migration file in `database/migrations/tenant`:

```bash
php artisan make:tenant-migration create_posts_table --create=posts
```

To update a table, use:

```bash
php artisan make:tenant-migration add_status_to_posts_table --table=posts
```

For the base tenant (admin table), use:

```bash
php artisan make:migration create_example_table --path=database/migrations/base
```

<em><strong>Once the above steps are complete, make sure to run this:</strong></em>

```bash
php artisan tenants:migrate
```

<em><strong>If you run the usual Laravel migration without this, errors will occur.</strong></em>

```bash
php artisan migrate
```

When creating routes, be sure to use Middleware.

Reference: `routes/admin/admin.php`

This is the admin middleware. Anything written here will be accessible routes.

```bash
Route::prefix('backend/admin')->name('admin.')->middleware('set.tenant')
```

These routes require authentication:

```bash
Route::middleware(['admin.auth'])
```

Reference: `routes/tenants/tenant.php`

This is the client tenant middleware, which is also necessary:

```bash
Route::prefix('backend/{tenant}')
    ->middleware('set.tenant')  // Middleware to load tenant
```

These routes require client authentication:

```bash
 Route::middleware(['tenant.auth'])
```

## Basic Authentication

Basic authentication is stored in `/storage/tenants/.htpasswd`. It's fine, as Laravel's storage is not publicly accessible. You can use the following symlink command safely.

Once you create a client, Basic Authentication will be issued, and you can reset it via the admin panel.

```bash
php artisan storage:link
```

The symlink will link `/storage/app` to `/public`, so it’s safe.

## Creating Tenants during Development

When creating tenants, this step is necessary because without it, the tenant-specific middleware won’t work.

For JS URLs, send the tenant parameter yourself.

```bash
{{ route('tenant.users.check_login', ['tenant' => $tenant_name]) }} // <- ['tenant'] is required
```

## Installing JS

You can install via NPM or CDN. You must use NPM to use JS, as Vite will minify and encrypt it.

Example:

```bash
npm install sweetalert2
```

## Using Axios

Asynchronous:

```bash
const response = await axios.post('/api/backend/admin/tenant_users', data);
console.log(response);
```

Synchronous:

```bash
axios.post('/api/persons/unique/alias', {
        params: {
            id: this.id,
            alias: this.alias,
        }
    })
    .then((response) => {
        console.log('2. server response:' + response.data.unique)
        this.valid = response.data.unique;
    });
```

When using PUT and DELETE in Laravel, always include `_method` for security.

```bash
var data = { _method: 'DELETE' };
const response = await axios.post('/api/backend/admin/tenant_users', data);
var data = { _method: 'PUT' };
const response = await axios.post('/api/backend/admin/tenant_users', data);
```

## Using SweetAlert2

```bash
Swal.fire({
    icon: 'question',
    title: langs.ask_create.replace(':data', 'site maintenance'),
    html: data.modal_html,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: langs.yes,
    cancelButtonText: langs.no,
    customClass: {
        input: 'my-swal-input',
        confirmButton: 'btn btn-primary custom-confirm-button',
        cancelButton: 'btn btn-secondary'
    },
    allowOutsideClick: false,
    allowEscapeKey: false,
    preConfirm: () => {
        // This callback will return false initially, preventing the modal from closing
        return false;
    },
    didOpen: () => {
        var frontSiteChecked = jsonData?.front_site === 'frontend' ? true : false;
        var backSiteChecked = jsonData?.back_site === 'backend' ? true : false;
        var maintenanceMode = jsonData?.maintenance_0;
        var maintenanceTermStart = jsonData?.maintenance_term?.maintanance_term_start || '';
        var maintenanceTermEnd = jsonData?.maintenance_term?.maintanance_term_end || '';
        var allowIp = jsonData?.allow_ip?.join('\n'); // Join IPs with newline separator
        var frontMessage = jsonData?.front_main_message || '';
        var backMessage = jsonData?.back_main_message || '';
        console.log([frontSiteChecked, backSiteChecked, maintenanceMode, maintenanceTermStart, maintenanceTermEnd, allowIp, frontMessage, backMessage]);

        // Set the values for the modal inputs and textareas
        $('input[name="front_site_modal"]').prop('checked', frontSiteChecked);
        $('input[name="back_site_modal"]').prop('checked', backSiteChecked);
        $('input[name="maintenance_0_modal"][value="' + maintenanceMode + '"]').prop('checked', true);
        $('textarea[name="allow_ip_modal"]').val(allowIp);
        $('textarea[name="front_main_message_modal"]').val(frontMessage);
        $('textarea[name="back_main_message_modal"]').val(backMessage);

        $('#maintenance_term_modal').text(maintenanceTermStart + ' to ' + maintenanceTermEnd);
        flatpickr("#maintenance_term_modal", {
            mode: 'range',
            enableTime: true,
            dateFormat: "Y-m-d H:i:S",
            time_24hr: true,
            defaultDate: [
                $('#maintenance_term_modal').data('start'),
                $('#maintenance_term_modal').data('end')
            ]
        });

        const confirmButton = Swal.getConfirmButton();
        if (confirmButton) {
            confirmButton.addEventListener('click', async () => {
                // Collect form data from the modal
                var frontSiteChecked = $('input[name="front_site_modal"]:checked').val();
                var backSiteChecked = $('input[name="back_site_modal"]:checked').val();
                var maintenanceMode = $('input[name="maintenance_0_modal"]:checked').val();
                var maintenanceTerm = $('input[name="maintenance_term_modal"]').val();
                var allowIp = $('textarea[name="allow_ip_modal"]').val();
                var frontMessage = $('textarea[name="front_main_message_modal"]').val();
                var backMessage = $('textarea[name="back_main_message_modal"]').val();

                // Create FormData object
                var formData = new FormData();
                formData.append('front_site', frontSiteChecked);
                formData.append('back_site', backSiteChecked);
                formData.append('maintenance_0', maintenanceMode);
                formData.append('maintenance_term', maintenanceTerm);
                formData.append(`allow_ip`, allowIp);
                formData.append('front_main_message', frontMessage);
                formData.append('back_main_message', backMessage);
                formData.append('tenant', user

Tenant);

                // Send data to backend for processing
                await axios.post('/backend/api/maintenance', formData)
                    .then(response => {
                        if (response.status === 200) {
                            Swal.fire('Saved Successfully!');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error saving data!');
                    });
            });
        }
    }
})
```

Let me know if you need any further clarification!