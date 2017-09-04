'use strict';

var request = require('request');
var cheerio = require('cheerio');

let url = "https://swgoh.gg/g/11339/the-phantom-schwartz/";
let memberBase = "https://swgoh.gg/";
let memberTag = "collection/";

console.log(process.env.DATABASE_URL)

// getGuild(url);

// parseMembers(['u/matthillman/']).then(list => console.log(list)).catch(error => console.error(error));

function getGuild(url) {
	request(url, (error, response, html) => {
	    if (error) {
	        console.error("Error loading url", error);
	        return;
	    }
	
	    let $ = cheerio.load(html);
	
	    var memberLinks = $('.character-list table tbody tr td:first-of-type > a').map((index, member) => {
	        return member.attribs.href;
	    });
	    parseMembers(memberLinks.toArray()).then(function(list) {
	        list.forEach(member => console.log(member.name, member.characters.length));
	    });
	});
}

function parseMembers(links) {
    return new Promise((resolve, reject) => {
        let members = [];
        links.forEach((href) => {
            getMember(href).then(memberMain => {
                let name = cheerio(memberMain).find('.panel-profile').last().find('.panel-title .char-name').first().text();
                let chars = cheerio(memberMain).find('.player-char-portrait').map((index, char) => {
                    let $char = cheerio(char);
                    return {
                        name: $char.find('img').first().attr('alt'),
                        level: $char.find('.char-portrait-full-level').text(),
                        gear: $char.find('.char-portrait-full-gear-level').text(),
                        stars: 7 - $char.find('star-inactive').length,
                    };
                });
                members.push({ name: name, characters: chars});
                
                if (links.length == members.length) {
                    members.sort((a, b) => {
                        let nameA = a.name.toUpperCase();
                        let nameB = b.name.toUpperCase();
                        return nameA < nameB ? -1 : (nameA > nameB ? 1 : 0);
                    })
                    resolve(members);
                }
            });
        });
    });
}

function getMember(href) {
    return new Promise((resolve, reject) => {
        request(memberBase + href + memberTag, (error, response, html) => {
            if (error) return reject(error);
            resolve(html);
        });
    });
}