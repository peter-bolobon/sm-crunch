This is a solution for the SM API assignment.

## Requirements

- PHP 7.4
- Composer

## Install, configure and run

1. Install the Composer autoloader
2. Create the `.env` file.

```
composer install --no-dev
cp .env.example .env
```

3. Specify connection details in the `.env` file.
4. Run the solution.

```
php crunch.php
```

### .env configuration

- `ENDPOINT` -- SM API endpoint.
- `CLIENT_ID` -- API client identifier.
- `EMAIL` -- API client email.
- `NAME` -- API client name.
- `CA_PATH` -- *(optional)* Path to the custom CA bundle.

## Notes regarding the endpoint

- Invalid SL token message returns with a `500 Internal Server Error` code. `401 Not Authorized` is a more appropriate and standards-compliant code for this case.
- Querying pages out of the 1-10 range (e.g. negative integers, 42, etc.) produces seemingly legit collections. A more optimal interface would either throw an out-of-bounds error or produce an empty collection. It is still possible to tell the difference since an out-of-bounds response indicates a mismatching page number -- but such behavior requires more attention on the consumer's behalf.
