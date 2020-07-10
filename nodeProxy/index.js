const express = require('express')
const cors = require('cors')
const { Curl  } = require('node-libcurl');
const DataScrapper =  require("./dataScrapper");
// const Phantom = require("./phantom")
const app = express()

const corsOpts = {
    origin: '*',
    methods: [
      'GET',
      'POST',
    ],
    allowedHeaders: [
      'Content-Type',
    ],
};

app.use(cors(corsOpts));
app.use(function(req, res, next) {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
    res.setHeader('Access-Control-Allow-Credentials', true);
    next();
});
app.use(express.json())

app.post('/api/get', (req, response)=>{
    console.log(req.body);

    const urls = req.body.urls
    const keys = req.body.keys
    const scrapeURLs = []
    const resArr = []
    urls.forEach(async (url) => {
        console.log("fetching data from url: " + url);
        console.time("fetching data from url start: =============")
        // keys.forEach(key => {
            for (var i=0; i<keys.length; i++) {
                const result = await DataScrapper.scrap(url, keys[i]);
                resArr.push(result)
            }
        // })
        console.timeEnd("fetching data from url end: =============")
    })
    if(resArr.length === urls.length)
        response.status(200).json({msg: resArr})
    // scrapeURLs.forEach(async (url)=>{
        // console.log(result)
        // const curl = new Curl();
        // curl.setOpt(Curl.option.URL, url);
        // curl.setOpt(Curl.option.HEADER, true);
        // curl.setOpt(Curl.option.ACCEPT_ENCODING, 'gzip');
        // curl.setOpt(Curl.option.FOLLOWLOCATION, true);
        // curl.setOpt(Curl.option.SSL_VERIFYPEER, false);
        // curl.setOpt(Curl.option.USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.106 Safari/537.36');

        // curl.on('end', function (statusCode, data, headers) {
        //     console.log("fetched data from url:============== " + url);
        //     // console.info(statusCode);
        //     console.info(data);
        //     // console.info('------------------------');
        //     // console.info(this.getInfo( 'TOTAL_TIME'));
        //     this.close();
        //     resArr.push(data);
        //     if(resArr.length === urls.length)
        //         response.status(200).json({msg: resArr})
        // });
    
        // curl.on('error', function(error){
        //     console.log(error);
        // });
        // curl.perform();
            
    // })
})

app.listen(5000);

console.log("Node proxy is running at port: 5000.")