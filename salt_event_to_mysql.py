#!/bin/env python
#coding=utf8

# Import python libs
import json

# Import salt modules
import salt.config
import salt.utils.event

# Import third party libs
import MySQLdb

import re

__opts__ = salt.config.client_config('/etc/salt/master')

# Create MySQL connect
conn = MySQLdb.connect(host=__opts__['mysql.host'], user=__opts__['mysql.user'], passwd=__opts__['mysql.pass'], db=__opts__['mysql.db'], port=__opts__['mysql.port'])
cursor = conn.cursor()
sock_dir = '/var/run/salt/master'
# Listen Salt Master Event System
#event = salt.utils.event.MasterEvent(__opts__['sock_dir'])
event = salt.utils.event.MasterEvent(sock_dir)
event_job_returns_match = 'salt/job/[0-9]*/ret/\w+'
event_job_new_match = 'salt/job/[0-9]*/new'
for eachevent in event.iter_events(full=True):
    print eachevent
    ret = eachevent['data']
    # if ret['tag'] != '':
    # filter out events with an empty tag. those are special
    if eachevent['tag'] != '':
    # run through our configured events and try to match the
    # current events tag against the ones we're interested in
	match1 = re.compile(event_job_returns_match)
	match2 = re.compile(event_job_new_match)
        #if event_job_returns_match.match(eachevent['tag']):
        if match1.match(eachevent['tag']):
            sql = '''INSERT INTO `salt_job_returns`
                (`jid`, `rec_date`, `returns`, `success`, `retcode`, `host_id`)
                VALUES (%s, %s, %s, %s, %s, %s)'''
            cursor.execute(sql, (ret['jid'], ret['_stamp'],
                                 json.dumps(ret['return']),
                                 ret['success'], ret['retcode'], ret['id']))
            cursor.execute("COMMIT")

        if match2.match(eachevent['tag']):
            sql = '''INSERT INTO `salt_job`
                (`jid`, `user`, `fun`, `arg`, `tgt`, `tgt_type`, `started_date`)
                VALUES (%s, %s, %s, %s, %s, %s, %s)'''
            cursor.execute(sql, (ret['jid'], ret['user'],
                                 ret['fun'], json.dumps(ret['arg']),
                                 json.dumps(ret['tgt']), ret['tgt_type'], ret['_stamp']))
            cursor.execute("COMMIT")

    #if "salt/job/" in eachevent['tag']:
    #    # Return Event
    #    if ret.has_key('id') and ret.has_key('return'):
    #        # Igonre saltutil.find_job event
    #        if ret['fun'] == "saltutil.find_job":
    #            continue

    #        #sql = '''INSERT INTO `salt_returns`
    #        #    (`fun`, `jid`, `return`, `id`, `success`, `full_ret` )
    #        #    VALUES (%s, %s, %s, %s, %s, %s)'''
    #        #cursor.execute(sql, (ret['fun'], ret['jid'],
    #        #                     json.dumps(ret['return']), ret['id'],
    #        #                     ret['success'], json.dumps(ret)))

    #        sql = '''INSERT INTO `salt_job_returns`
    #            (`jid`, `rec_date`, `returns`, `success`, `retcode`, `host_id`)
    #            VALUES (%s, %s, %s, %s, %s, %s)'''
    #        cursor.execute(sql, (ret['jid'], ret['_stamp'],
    #                             json.dumps(ret['return']),
    #                             ret['success'], ret['retcode'], ret['id']))
    #        cursor.execute("COMMIT")
    # Other Event
    else:
        pass
_
