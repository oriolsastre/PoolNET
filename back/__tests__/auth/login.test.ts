import request from "supertest";
import { API_URL, TEST_USER } from "../constants";

const req = request(`${API_URL}/auth`);

describe("Testejant l'endpoint de login", () => {
  it("Hauria de loguejar-se amb credencials correctes i rebre el token", async () => {
    const response = await req.post(`/login/`).send(TEST_USER);
    const cookie = response.header["set-cookie"] as string[];

    expect(response.status).toBe(200);
    expect(response.body.token).toBeDefined();
    expect(cookie[0]).toMatch(/token/);
  });
  describe("Testejant error", () => {
    it("Hauria de sortir error en loguejar-se amb credencials incorrectes", async () => {
      const response = await req.post(`/login/`).send({
        usuari: "Test",
        password: "Test123",
      });

      expect(response.status).toBe(400);
      expect(response.body.error).toBe("Error amb les credencials.");
    });
    it("Hauria de sortir error si no s'envia body", async () => {
      const response = await req.post(`/login/`);

      expect(response.status).toBe(400);
      expect(response.body.error).toBe("Error amb les credencials.");
    });
  });
});
