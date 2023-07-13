import request from "supertest";
import { API_URL } from "../constants";

const req = request(`${API_URL}/control`);

function isNullOrNumber(value: any) {
  return value === null || typeof value === "number";
}

describe("Testejant l'endpoint GET de control", () => {
  it("Hauria de rebre un array amb els últims controls", async () => {
    const response = await req.get('/');

    expect(response.status).toBe(200);
    expect(response.body).toBeInstanceOf(Array);
    expect(response.body.length).toBeLessThanOrEqual(20)
    response.body.forEach((control: any) => {
      expect(typeof control.controlID).toBe("number");
      expect(typeof control.data_hora).toBe("string");
      expect(isNullOrNumber(control.ph)).toBe(true);
      expect(isNullOrNumber(control.clor)).toBe(true);
      expect(isNullOrNumber(control.alcali)).toBe(true);
      expect(isNullOrNumber(control.temperatura)).toBe(true);
      expect(isNullOrNumber(control.transparent)).toBe(true);
      expect(isNullOrNumber(control.fons)).toBe(true);
      expect(typeof control.usuari).toBe("number");
      expect(typeof control.user).toBe("object");
      expect(typeof control.user.userID).toBe("number");
      expect(typeof control.user.usuari).toBe("string");
      expect(typeof control.user.nivell).toBe("number");
    })
  });
});
