## Laravel Encryption
Combining RSA + AES Encryption to secure REST Endpoint With Sensitive Data

## Installation
- Create new directory `keys` on server-app/storage and client-app/storage
- Go to directory server-app, and run command 
```
openssl genpkey -algorithm RSA -out storage/keys/private_key.pem -pkeyopt rsa_keygen_bits:2048
openssl rsa -pubout -in storage/keys/private_key.pem -out storage/keys/public_key.pem

```

- Copy server-app/storage/keys/public_key.pem to client-app/storage/keys/public_key.pem
- Run command on server-app `php artisan serve --port=8000`
- Run command on client-app `php artisan serve --port=8001`
- Now hit endpoint from client-app 
    - Method `POST`
    - Base url `http://localhost:8001/api/send-encrypted-data`  