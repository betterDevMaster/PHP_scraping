const https = require('https');
const axios = require('axios');
const cheerio = require('cheerio');
const request = require('request');
const puppeteer = require('puppeteer');
const youtube = require('./youtube');

const DataScrapper = {
    scrap: async function(parcelURL, key){
        parcelURL = parcelURL + key

        try{
            return await this.scrapNext(parcelURL);
        }catch(e){
            console.error(e);
        }finally{
            // 
        }
        return {parcel: '', address:'', city: ''};
    },
    scrapNext: async function(url){
        var retObj = {};
        try{
            const html = await this.readHtml(url);
            console.log(url)
            const $ = cheerio.load(html.data);
            // const startpoint = html.data.indexOf('imagePathList') + 16;
            // const endpoint = html.data.indexOf('summImagePathList') - 24;
            // const imageUrlStr = html.data.substring(startpoint, endpoint);
            // const imageUrlList = imageUrlStr.split(',');
            // retObj.title = $("title").html().substring(0, $("title").html().length-33);
            // retObj.image1_URL = imageUrlList[0].substring(1, imageUrlList[0].length-1);
            // retObj.image2_URL = imageUrlList[1].substring(1, imageUrlList[1].length-1);

            if (url.includes('tiktok')) {
                var string = $('h2.share-sub-title-thin strong').html()
                console.log('---------', html.data, string)
                // var string = html.data.find('h2.share-sub-title-thin')
                
                // console.log('string:======= ',string, $('h2.share-sub-title-thin').children());
                // var numbers = string.match(/[+-]?\d+(?:\.\d+)?/g).map(Number)
                
                // console.log('numbers:======= ',numbers);
                // return {site: 'tiktok', penetration:numbers[0], sentiment: ''};
            }
            // console.log('result---', retObj);

            // retObj.image1_URL = $(".images-view-item")[0].children[0].getAttribute('src');
            // retObj.title = $(".images-view-item")[1].children[0].getAttribute('src');

            // const strongs = $("strong")
            // for (let i = 0; i < strongs.length; i++) {
            //     // const a = $(strongs[i]).find('td > a')[0];
            //     if(strongs[i].children){
            //         switch(strongs[i].children[0].data){
            //             case 'PIN:':
            //                 retObj.PIN = $(strongs[i].parent.children[1]).text().trim();
            //                 break;
            //             case 'Street Address:':
            //                 retObj.Address = $(strongs[i].parent.children[1]).text().trim();
            //                 break;
            //             case 'City:':
            //                 retObj.City = $(strongs[i].parent.children[1]).text().trim();
            //                 break;
            //             case 'Zipcode:':
            //                 retObj.Zip = $(strongs[i].parent.children[1]).text().trim();
            //                 break;
            //             case 'Latitude:':
            //                 retObj.Latitude = $(strongs[i].parent.children[1]).text().trim();
            //                 break;
            //             case 'Longitude:':
            //                 retObj.Longitude = $(strongs[i].parent.children[1]).text().trim();
            //                 break;
            //             case 'Tax Set:':
            //                 retObj.Tax = $(strongs[i].parent.children[1]).text().trim();
            //                 break;
            //             case 'Date Updated:':
            //                 retObj.Updated = $(strongs[i].parent.children[1]).text().trim();
            //                 break;
            //             case 'Blockchain:':
            //                 retObj.Blockchain = $(strongs[i].parent.next.next).text().trim();
            //                 break;
            //             case 'Cook County GIS Link:':
            //                 retObj.CookUrl = strongs[i].next.next.attribs.href;
            //                 break;
            //             case 'Google Maps Link:':
            //                 retObj.Map = strongs[i].next.next.attribs.href;
            //                 break;
            //             case 'Record ID:':
            //                 retObj.Record = $(strongs[i].next).text().trim();
            //                 break;
            //         }
            //     }
            // }
            return retObj;
        }catch(e){
            console.error(e);
        }
        // return {parcel: '', address:'', city: ''};
        return {title: '', image1_URL:'', image2_URL: ''};
    },
    readHtml: function(url){
        const agent = new https.Agent({  
            rejectUnauthorized: false
        });
        console.log("agent --- ", agent)
        console.log("axios", axios.get(url,{ httpsAgent: agent }));
        return axios.get(url,{ httpsAgent: agent });
    }

}
module.exports = DataScrapper;