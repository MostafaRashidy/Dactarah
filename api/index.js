const { createServer } = require("http");
const { parse } = require("querystring");

module.exports = (req, res) => {
    req.query = parse(req.url.split("?")[1]);
    return createServer((req, res) => {
        require("../public/index.php");
    }).emit("request", req, res);
};
