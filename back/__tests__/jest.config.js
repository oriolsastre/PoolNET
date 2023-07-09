/** @type {import('ts-jest').JestConfigWithTsJest} */
module.exports = {
  preset: "ts-jest",
  testEnvironment: "node",
  testPathIgnorePatterns: [
    "/node_modules/",
    "constants.ts",
    "constants.example.ts",
    "jest.config.js",
    "setup.ts",
  ],
  globalSetup: "./setup.ts"
};
