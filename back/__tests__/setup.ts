import request from "supertest";
import { API_URL, TEST_USER, TEST_ADMIN } from "./constants";

const req = request(`${API_URL}/auth`);

module.exports = async () => {
  const response = await req.post(`/login/`).send(TEST_USER);
  const cookie = response.header["set-cookie"] as string[];
  const token: string = cookie[0];
  process.env.TOKEN_NIVELL1 = token;

  const response2 = await req.post(`/login/`).send(TEST_ADMIN);
  const cookie2 = response2.header["set-cookie"] as string[];
  const token2: string = cookie2[0];
  process.env.TOKEN_NIVELL0 = token2;
};
