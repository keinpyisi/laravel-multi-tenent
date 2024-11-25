Here's the translation of the provided text:

---

-   [English](README.en.md)
-   [日本語](README.md)

# Creating Multiple Clients in Laravel

This is a Laravel environment that can manage multiple clients, similar to CodeIgniter's BaseClient.

The admin panel is written with Tailwind CSS. Models and other elements use SweetAlert2, but you can <em><strong>create your own individual tenant "clients"</strong></em> if necessary.

In this project, we use Axios instead of Ajax because Ajax is weak in terms of security.

Reference: [OWASP](https://cheatsheetseries.owasp.org/cheatsheets/AJAX_Security_Cheat_Sheet.html)

## Setup

Please [install Composer](https://getcomposer.org/download/).
Any version will work fine.

Install NodeJS v23 [here](https://nodejs.org/en/download/package-manager). The reason for this is that it automatically minifies and encrypts JS when writing.

Then, open CMD here and run:

```bash
composer install
```

```bash
npm install
```

If you can't install NPM, it's fine to upgrade to the latest version using `npm update`.

<em><strong>If you want to upgrade Laravel as well, you don't need to worry about it, just run this:</strong></em>

```bash
composer update
```

Next, copy the `.env.local` file and set a new key.

<em><strong>When you want to view data from the server, make sure the APP_KEY in .env matches the key on the server.</strong></em>

```bash
php artisan key:generate
```

Now, create a database in PostgreSQL. Once done, set it like this in `.env`:

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=YourCreatedDBName
DB_USERNAME=YourDBUsername
DB_PASSWORD=YourDBPassword
```

After this, let's create the base schema.

Run this in CMD.

This will create the `base_client` schema, where admin panel information is stored.

```bash
php artisan tenant:create-base-schema --seed
```

You can find the developer's username and password for the admin panel here. Feel free to change it.

```bash
database/seeders/AdminSeeder.php
```

Now, let's create the `base_client` schema.

```bash
php artisan setup:base-client --seed
```

This will create the base DB for clients.

If you're working locally, open two CMD windows.

Then, run the following in one of them:

```bash
php artisan serve
```

In the other:

```bash
npm run dev
```

While these are running, you can develop.

## Release Preparation

Before releasing, always run this and upload everything from `/public/build/`:

```bash
npm run build
```

## Development Process (Make Sure to Follow These Steps or Errors May Occur)

Here’s how to create a new migration file under `database/migrations/tenant`:

```bash
php artisan make:tenant-migration create_posts_table --create=posts
```

When updating a table, use this:

```bash
php artisan make:tenant-migration add_status_to_posts_table --table=posts
```

To make it a base tenant (admin table), use this:

```bash
php artisan make:migration create_example_table --path=database/migrations/base
```

<em><strong>Once you finish the steps above, make sure to run this:</strong></em>

```bash
php artisan tenants:migrate
```

<em><strong>If you run the usual Laravel migration command without this, it will cause errors.</strong></em>

```bash
php artisan migrate
```

## Using Tenants During Development

When routing, you need to specify the tenant:

The reason is that without this tenant, the tenant-specific middleware won't work.

Even when using JS, you need to send the parameters yourself.

```bash
{{ route('tenant.users.check_login', ['tenant' => $tenant_name]) }}
```

## Installing JS

Install it via NPM or CDN. Without NPM, you can’t use JS.

The reason is that Vite minifies and encrypts JS for you.

Example:

```bash
npm install sweetalert2
```

## Using AXIOS

Asynchronous:

```bash
const response = await axios.post('/api/backend/admin/tenent_users', data);
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

When using Laravel’s PUT and DELETE, make sure to add `_method`:

This is for Laravel security.

```bash
var data = { _method: 'DELETE' };
const response = await axios.post('/api/backend/admin/tenent_users', data);
var data = { _method: 'PUT' };
const response = await axios.post('/api/backend/admin/tenent_users', data);
```

## Using SweetAlert2

```bash
Swal.fire({
    icon: 'question',
    title: langs.ask_create.replace(':data', 'Maintenance for each site'),
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
        return false; // Prevent modal from closing initially
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

        // Update modal values
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
            defaultDate: [$('#maintenance_term_modal').data('start'), $('#maintenance_term_modal').data('end')]
        });

        const confirmButton = Swal.getConfirmButton();
        if (confirmButton) {
            confirmButton.addEventListener('click', async () => {
                var frontSiteChecked = $('input[name="front_site_modal"]:checked').val();
                var backSiteChecked = $('input[name="back_site_modal"]:checked').val();
                var maintenanceMode = $('input[name="maintenance_0_modal"]:checked').val();
                var maintenanceTerm = $('input[name="maintenance_term_modal"]').val();
                var allowIp = $('textarea[name="allow_ip_modal"]').val();
                var frontMessage = $('textarea[name="front_main_message_modal"]').val();
                var backMessage = $('textarea[name="back_main_message_modal"]').val();

                // Prepare form data
                var formData = new FormData();
                formData.append('front_site', frontSiteChecked);
                formData.append('back_site', backSiteChecked);
                formData.append('maintenance_0', maintenanceMode);
                formData.append('maintenance_term', maintenanceTerm);
                formData.append('allow_ip', allowIp);
                formData.append('front_main_message', frontMessage);
                formData.append('back_main_message', backMessage);
                formData.append('tenant', user_id);
                formData.append('_method', 'PUT');

                try {
                    const response = await axios.post(`/api/backend/admin/maitenances/${user_id}/update`, formData);

                    if (response.data.type === 'error') {
                        var errorMessages = response.data.data;
                        var errorMessage = errorMessages.join('<br>');
                        Swal.showValidationMessage(errorMessage.trim());
                        return;
                    } else {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: langs.success_title,
                            text: langs.success.replace(':attribute', langs.account),
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error!', 'There was an issue with your request.', 'error');
                    return;
                }
            });
        }
    }
});
```

That’s all!
