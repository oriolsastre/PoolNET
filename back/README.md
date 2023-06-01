API senzilla.

## Docs

Hi ha 3 endpoints, 1 de login i els altres dos per a funcionalitats CRUD d'accions i controls sobre l'aigua.

## Auth ``/api/auth/login``
### POST
- Body:
```js
{
  usuari: String,
  password: String
}
```
- Respostes
1. 204 + cookie amb JWT per a rutes protegides
2. 400: ``{message: "Error amb les credencials"}``

## Control ``/api/control/``
### GET
Obté els últims 20 controls sobre l'estat de l'aigua que s'han realitzat a la piscina.

Resposta
```javascript
{
  data: [
    {
      id: integer,
      data_hora: timestamp,
      ph: float?,
      clor: float?,
      alcali: float?,
      temperatura: integer?,
      transparent: integer?,
      fons: integer?,
      usuari: integer,
    }
  ]
}
```

### POST
- Headers: Token obtingut al login.
```
{Cookie: "token=[JWT]"}
```
- Body:
```js
{
  ph: float?,
  clor: float?,
  alcali: float?,
  temperatura: integer?,
  transparent: integer?,
  fons: integer?
}
```
- Resposta: 201 si tot Ok; 400 en cas contrari.