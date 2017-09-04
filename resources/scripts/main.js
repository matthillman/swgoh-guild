'use strict';

const request = require('request');
const $ = require('cheerio');

const { Pool, Client } = require('pg');

const memberBase = "https://swgoh.gg/";
const memberTag = "collection/";

const pool = new Pool({
    connectionString: process.env.DATABASE_URL,
});

const upsertUserQ = "insert into members (name, guild_id, slug, power, character_power, ship_power) values ($1, $2, $3, $4, $5, $6) \
on conflict (slug) do update set guild_id = $2, power = $4, character_power = $5, ship_power = $6 where slug = $3; \
";

pool.query('select url from guilds where id = $1', (process.argv[2] || 1))
    .then(res => console.warn(res))
    .catch(e => console.error(e.stack));

// const url = "https://swgoh.gg/g/11339/the-phantom-schwartz/";
// getGuild(url);

// parseMembers(['u/matthillman/']).then(list => console.log(list)).catch(error => console.error(error));

function getGuild(url) {
	request(url, (error, response, html) => {
	    if (error) {
	        console.error("Error loading url", error);
	        return;
	    }
	
	    let $members = $.load(html);
	
	    var memberLinks = $members('.character-list table tbody tr td:first-of-type > a').map((index, member) => {
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
                let name = $(memberMain).find('.panel-profile').last().find('.panel-title .char-name').first().text();
                let chars = $(memberMain).find('.player-char-portrait').map((index, char) => {
                    let $char = $(char);
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