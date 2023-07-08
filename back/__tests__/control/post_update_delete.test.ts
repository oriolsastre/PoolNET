import request from "supertest";
import { API_URL } from "../constants";

const req = request(`${API_URL}/control`);
const token = process.env.TOKEN_NIVELL1 as string;
const cookie = [token];
const tokenAdmin = process.env.TOKEN_NIVELL0 as string;
const cookieAdmin = [tokenAdmin];

const ultimControl: Array<any> = [];
describe("Testejant l'endpoint POST de control", () => {
  describe("Testejant error", () => {
    it("Hauria de fallar sense credencials", async () => {
      const response = await req.post("/");
      expect(response.status).toBe(401);
      expect(response.body.error).toBe("No autoritzat");
    });
    it("Hauria de fallar amb credencials però sense dades enviades", async () => {
      const response = await req.post("/").send({}).set("Cookie", cookie);
      expect(response.status).toBe(400);
      expect(response.body.error).toBe("Mínim has d'omplir un camp.");
    });
    it("Hauria de fallar amb credencials i dades enviades, però són null", async () => {
      const response = await req
        .post("/")
        .send({
          ph: null,
          clor: null,
          alcali: null,
        })
        .set("Cookie", cookie);
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
      const body = {
        ph: 7.2,
        clor: 0.1,
        alcali: 1,
        transparent: 1,
        temperatura: 28,
        fons: 1,
      };
      const response = await req
        .post("/")
        .send(body)
        .set("Cookie", cookieAdmin);
      expect(response.status).toBe(204);
      const getAll = await req.get("/");
      ultimControl.push(getAll.body[0]);
      expect(getAll.body[0]).toEqual(expect.objectContaining(body));
    });
    it("Hauria de crear un control amb només algunes dades proporcionades", async () => {
      const body = {
        ph: 7.2,
        clor: 0.1,
      };
      const response = await req.post("/").send(body).set("Cookie", cookie);
      expect(response.status).toBe(204);
      const getAll = await req.get("/");
      ultimControl.push(getAll.body[0]);
      expect(getAll.body[0]).toEqual(expect.objectContaining(body));
    });
  });
});
describe("Testejant l'endpoint PATCH de control", () => {
  describe("Testejant error", () => {
    it("Hauria de fallar sense credencials", async () => {
      const response = await req.patch("/");
      expect(response.status).toBe(401);
      expect(response.body.error).toBe("No autoritzat");
    });
    describe("Amb credencials", () => {
      describe("Problemes de format", () => {
        it("Hauria de fallar sense dades enviades", async () => {
          const response = await req.patch("/").send({}).set("Cookie", cookie);
          expect(response.status).toBe(400);
          expect(response.body.error).toBe("Falta algun camp obligatori.");
          expect(response.body.camps_obligatoris).toBeInstanceOf(Object);
        });
        it("Hauria de fallar amb valor invàlid", async () => {
          const response = await req.patch("/").set("Cookie", cookie).send({
            controlID: ultimControl[1].controlID,
            ph: 7.0,
            transparent: "Molt",
          });
          expect(response.status).toBe(400);
          expect(response.body.error).toBeDefined();
        });
        it("Hauria de fallar amb tot null", async () => {
          const response = await req.patch("/").set("Cookie", cookie).send({
            controlID: ultimControl[1].controlID,
            ph: null,
            clor: null,
            alcali: null,
            transparent: null,
            temperatura: null,
            fons: null,
          });
          expect(response.status).toBe(400);
          expect(response.body.error).toBe("No pots buidar un control.");
        });
        it("Hauria de fallar si no troba el control", async () => {
          const response = await req.patch("/").set("Cookie", cookie).send({
            controlID: -5,
            ph: 7.2,
            clor: 0.1,
            alcali: 1.1,
            transparent: 1,
            temperatura: 28,
          });
          expect(response.status).toBe(404);
          expect(response.body.error).toBe("No s'ha trobat el control.");
        });
      });
      describe("Problemes de permisos", () => {
        it("Hauria de fallar si intenta modificar un control aliè", async () => {
          const response = await req.patch("/").set("Cookie", cookie).send({
            controlID: ultimControl[0].controlID,
            ph: 8.2,
            clor: 0.1,
            alcali: null,
            transparent: 1,
            temperatura: 28,
            fons: 1,
          });
          expect(response.status).toBe(403);
          expect(response.body.error).toBe(
            "Només pots editar controls propis."
          );
        });
      });
    });
  });
  describe("Testejant l'èxit", () => {
    it("Hauria de modificar un control propi", async () => {
      const body = {
        controlID: ultimControl[1].controlID,
        ph: 8.1,
        clor: 0.1,
        alcali: null,
        transparent: 1,
        temperatura: 28,
        fons: 1,
      };
      const response = await req.patch("/").send(body).set("Cookie", cookie);
      expect(response.status).toBe(204);
      const getAll = await req.get("/");
      expect(getAll.body[0]).toEqual(expect.objectContaining(body));
    });
    it("L'admin hauria de poder editar un control aliè", async () => {
      const body = {
        controlID: ultimControl[1].controlID,
        ph: 4.1,
        clor: 3.5,
        fons: null,
      };
      const response = await req
        .patch("/")
        .send(body)
        .set("Cookie", cookieAdmin);
      console.log(response.body);
      expect(response.status).toBe(204);
      const getAll = await req.get("/");
      expect(getAll.body[0]).toEqual(expect.objectContaining(body));
    });
  });
});
describe("Testejant l'enpoint DELETE de control", () => {
  describe("Testejant l'error", () => {
    it("Hauria de fallar sense credencials", async () => {
      const response = await req.delete("/");
      expect(response.status).toBe(401);
      expect(response.body.error).toBe("No autoritzat");
    });
    describe("Amb credencials correctes", () => {
      it("Hauria de fallar sense body", async () => {
        const response = await req.delete("/").set("Cookie", cookie);
        expect(response.status).toBe(400);
        expect(response.body.error).toBe("Falta algun camp obligatori.");
        expect(response.body.camps_obligatoris).toBeInstanceOf(Object);
      });
      it("Hauria de fallar amb body invàlid (clau incorrecta)", async () => {
        const response = await req
          .delete("/")
          .send({ id: "1" })
          .set("Cookie", cookie);
        expect(response.status).toBe(400);
        expect(response.body.error).toBe("Falta algun camp obligatori.");
        expect(response.body.camps_obligatoris).toBeInstanceOf(Object);
      });
      it("Hauria de fallar amb body invàlid (valor incorrecte)", async () => {
        const response = await req
          .delete("/")
          .send({ controlID: "dos" })
          .set("Cookie", cookie);
        expect(response.status).toBe(400);
        expect(response.body.error).toBe(
          "Algun camp no és del tipus correcte."
        );
        expect(response.body.camps_obligatoris).toBeInstanceOf(Object);
      });
      it("Hauria de fallar si no troba el control", async () => {
        const response = await req
          .delete("/")
          .send({ controlID: -5 })
          .set("Cookie", cookie);
        expect(response.status).toBe(404);
        expect(response.body.error).toBe("No s'ha trobat el control.");
      });
      it("Hauria de fallar si intenta eliminar un control aliè", async () => {
        const response = await req
          .delete("/")
          .send({ controlID: ultimControl[0].controlID })
          .set("Cookie", cookie);
        expect(response.status).toBe(403);
        expect(response.body.error).toBe(
          "Només pots eliminar controls propis."
        );
      });
    });
  });
  describe("Testejant l'èxit", () => {
    it("Hauria de poder eliminar un control propi", async () => {
      const response = await req
        .delete("/")
        .send({ controlID: ultimControl.pop().controlID })
        .set("Cookie", cookie);
      expect(response.status).toBe(204);
    });
    it("L'admin hauria de poder eliminar un control aliè", async () => {
      const response = await req
        .delete("/")
        .send({ controlID: ultimControl.pop().controlID })
        .set("Cookie", cookieAdmin);
      expect(response.status).toBe(204);
    });
  });
});
