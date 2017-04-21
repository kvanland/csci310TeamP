#!/usr/bin/env python
__author__ = 'AlecFong'

import mechanize
import cookielib
import pickle
import time
import userConfig

class Login:

    def __init__(self):
        br = mechanize.Browser()
        # Cookie Jar
        self.cj = cookielib.LWPCookieJar()
        br.set_cookiejar(self.cj)

        # Browser options
        br.set_handle_equiv(True)
        br.set_handle_gzip(True)
        br.set_handle_redirect(True)
        br.set_handle_referer(True)
        br.set_handle_robots(False)
        br.set_handle_refresh(mechanize._http.HTTPRefreshProcessor(), max_time=1)
        self.br = br

    def login(self):
        print('attempting login...')
        self.br.open('https://libproxy.usc.edu/login')
        self.br.select_form(nr=0)

        response = self.br.submit().read()
        self.br.select_form(nr=0)
        response = self.br.submit().read()
        print('entering credentials...')
        self.br.select_form(nr=0)
        self.br.form['j_username'] = userConfig.username
        self.br.form['j_password'] = userConfig.password
        response = self.br.submit().read()
        if "form-error" in response:
            print('Invalid Login')
            return False
        print('verifying...')
        self.br.select_form(nr=0)
        response = self.br.submit().read()
        print('setting cookies...')
        cookies = []
        for cookie in self.cj:
            cookies.append(cookie)
        pickle.dump(cookies, open('cookies.p', 'wb'))
        return True

# start_time = time.time()
#
# l = Login()
# l.login()
#
# print('done')
# print("--- %s seconds login ---" % (time.time() - start_time))

