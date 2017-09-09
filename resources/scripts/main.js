'use strict';

const request = require('request');
const $ = require('cheerio');

const { Pool, Client } = require('pg');

const memberBase = "https://swgoh.gg/";
const memberTag = "collection/";

const pool = new Pool({
    connectionString: 'postgres://swgoh:swgoh@127.0.0.1:5432/swgoh',
});

const upsertUserQ = "insert into members (name, guild_id, slug, power, character_power, ship_power) values ($1, $2, $3, $4, $5, $6) \
 on conflict (slug) do update set guild_id = $2, power = $4, character_power = $5, ship_power = $6 where members.slug = $3; \
 ";
const upsertCharQ = "insert into characters (name, member_id, level, rarity, gear_level) values ($1, $2, $3, $4, $5) \
 on conflict (name, member_id) do update set level = $3, rarity = $4, gear_level = $5 where characters.name = $1 and characters.member_id = $2; \
 ";

const guildID = process.argv[2];
pool.query('select url from guilds where id = $1', [guildID])
    .then(res => {
        const url = res.rows[0].url;
        getGuild(url);
/*         parseMembers(['u/matthillman/'])
             .then(list => {
                 list.forEach(member => {
                     pool.query(upsertUserQ, [member.name, guildID, member.slug, member.power, member.characterPower, member.shipPower])
                         .then(res => {
                             member.characters.forEach(character => {
                                 pool.query('select id from members where slug = $1', [member.slug]).then(res => {
                                     pool.query(upsertCharQ, [character.name, res.rows[0].id, character.level, character.stars, character.gear])
                                         .then(res => console.info('Processed ' + member.name + ': ' + character.name))
                                         .catch(err => console.error('Error processing ' + member.name + ': ' + character.name, err));
                                 })
                             });
                         })
                         .catch(err => console.error('Error processing member ' + member.slug, err));
                 })
             })
             .catch(error => console.error(error)); */
    })
    .catch(e => console.error(e.stack));

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
        let memberIDList = [];
        parseMembers(memberLinks.toArray())
            .then(list => {
                list.forEach(member => {
                    pool.query(upsertUserQ, [member.name, guildID, member.slug, member.power, member.characterPower, member.shipPower])
                        .then(res => {
                            member.characters.forEach(character => {
                                pool.query('select id from members where slug = $1', [member.slug]).then(res => {
                                    let memberID = res.rows[0].id;
                                    pool.query(upsertCharQ, [character.name, memberID, character.level, character.stars, character.gear])
                                        .then(res => console.info('Processed ' + member.name + ': ' + character.name))
                                        .catch(err => console.error('Error processing ' + member.name + ': ' + character.name, err));
                                    memberIDList.push(memberID);
                                })
                            });
                        })
                        .catch(err => console.error('Error processing member ' + member.slug, err));
                    });
                })
                .catch(error => console.error(error));
            pool.query('delete from members where guild_id = $1 and slug not in ($2)', [guildID, memberLinks.toArray()])
                .then(res => console.info(res))
                .catch(err => console.error('Error deleting members', e.stack));
	});
}

function parseMembers(links) {
    return new Promise((resolve, reject) => {
        let members = [];
        links.forEach((href) => {
        	if (href == '/u/pi55%20off/') { return; }
            getMember(href).then(memberMain => {
                let $user = $(memberMain).find('.panel-profile').last();
                let name = $user.find('.panel-title .char-name').first().text();
                let $info = $user.find('.panel-body > p > .pull-right');
                let power = +($info.eq(0).text().replace(/,/g, ''));
                let charPower = +($info.eq(1).text().replace(/,/g, ''));
                let shipPower = +($info.eq(2).text().replace(/,/g, ''));
                let chars = $(memberMain).find('.player-char-portrait').map((index, char) => {
                    let $char = $(char);
                    return {
                        name: $char.find('img').first().attr('alt'),
                        level: +($char.find('.char-portrait-full-level').text()),
                        gear: fromRoman($char.find('.char-portrait-full-gear-level').text()),
                        stars: 7 - $char.find('.star-inactive').length,
                    };
                }).toArray();
                members.push({ name: name, slug: href, power: power, characterPower: charPower, shipPower: shipPower, characters: chars});
                
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

function fromRoman(str) {
    var result = 0;
    // the result is now a number, not a string
    var decimal = [1000, 900, 500, 400, 100, 90, 50, 40, 10, 9, 5, 4, 1];
    var roman = ["M", "CM", "D", "CD", "C", "XC", "L", "XL", "X", "IX", "V", "IV", "I"];
    for (var i = 0; i <= decimal.length; i++) {
        while (str.indexOf(roman[i]) === 0) {
            result += decimal[i];
            str = str.replace(roman[i], '');
        }
    }
    return result;
}
