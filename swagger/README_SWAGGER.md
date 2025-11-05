Swagger UI for Food Ordering API
================================

What I added
- `swagger/openapi.json` — a minimal OpenAPI 3 JSON describing a few endpoints (register, login, create order, read orders).
- `swagger/index.html` — static Swagger UI page that loads the spec from `/swagger/openapi.json` using the Swagger UI CDN.

How to use
1. Start the PHP built-in server from the project root (where this README sits):

```powershell
php -S localhost:8000 -t .
```

2. Open the Swagger UI in your browser:

http://localhost:8000/swagger/index.html

3. Try the endpoints by expanding them and using the "Try it out" button. Requests will be sent to `http://localhost:8000/...`.

Notes and next steps
- The spec is minimal and hand-written from the existing PHP endpoints. You may want to expand it to include request/response schemas for all endpoints.
- If you want automated API tests (Newman / Dredd), I can add a Postman collection or a Dredd config, but those require Node (newman) or installing Dredd via npm/composer.
- There was a database foreign-key error when running both DB setup scripts in the current environment; I can debug that next if you want the DB migrated cleanly.
