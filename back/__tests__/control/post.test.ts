import request from "supertest";
import { API_URL } from "../constants";

const req = request(`${API_URL}/control`);
const token = process.env.TOKEN_NIVELL1 as string;
const cookie = [token];

describe("Testejant l'endpoint POST de control", () => {
  describe("Testejant error", () => {
    it("Hauria de fallar sense credencials", async () => {
      const response = await req.post("/");

      expect(response.status).toBe(401);
      expect(response.body.message).toBe("No autoritzat");
    });

    it("Hauria de fallar amb credencials però sense dades enviades", async () => {
      const response = await req.post("/").send({}).set("Cookie", cookie);

      expect(response.status).toBe(400);
      expect(response.body.error).toBe("Mínim has d'omplir un camp.");
    });

    it("Hauria de fallar amb valor invàlid", async () => {
      const response = await req
        .post("/")
        .send({
          ph: 7.2,
          clor: 0.1,
          transparent: "Molt",
          temperatura: 28,
          fons: 1,
        })
        .set("Cookie", cookie);

      expect(response.status).toBe(400);
      expect(response.body.error).toBeDefined();
    });
  });
  describe("Testejant l'èxit", () => {
    it("Hauria de crear un control", async () => {
      const response = await req
        .post("/")
        .send({
          ph: 7.2,
          clor: 0.1,
          alcali: 1,
          transparent: 1,
          temperatura: 28,
          fons: 1,
        })
        .set("Cookie", cookie);

      expect(response.status).toBe(204);
      // TODO? Comprovar que existeix el registre a la DB
    });
    it("Hauria de crear un control amb només algunes dades proporcionades", async () => {
      const response = await req
        .post("/")
        .send({
          ph: 7.2,
          clor: 0.1,
        })
        .set("Cookie", cookie);

      expect(response.status).toBe(204);
      //TODO Accedir a DB i comprovar que la resta de valors són null
    });
  });
});
