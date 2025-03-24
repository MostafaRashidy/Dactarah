import { createServer } from "http";
import { parse } from "querystring";

export default (req, res) => {
    req.query = parse(req.url.split("?")[1]);
    return createServer((req, res) => {
        import("../public/index.php");
    }).emit("request", req, res);
};
