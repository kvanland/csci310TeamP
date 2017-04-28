#!/usr/bin/env python
__author__ = 'AlecFong'

import mechanize
import cookielib
import pickle
import sys
import login
import warnings
warnings.filterwarnings("ignore")

class GetPDF:

    def __init__(self, link):
        self.link = link

    def downLoadPDF(self, fileName, type):
        # print('retrieving cookies')
        newCookies = []
        try:
            newCookies = pickle.load(open('cookies.p', 'rb'))
        except IOError as ioe:
            if 'No such file or directory' in ioe:
                # print('no cookie file detected, logging in to create one')
                l = login.Login()
                if not l.login():
                    raise ValueError("Invalid Login")
                newCookies = pickle.load(open('cookies.p', 'rb'))
            else:
                raise ioe

        cjNew = cookielib.LWPCookieJar()
        # check if cookies are expired
        isExpired = False
        for cookie in newCookies:
            if(cookie.is_expired()):
                isExpired = True

        if isExpired:
            # print('Cookies expired, logging in')
            l = login.Login()
            if not l.login():
                raise ValueError("Invalid Login")
            newCookies = pickle.load(open('cookies.p', 'rb'))
            cjNew = cookielib.LWPCookieJar()

        for cookie in newCookies:
            cjNew.set_cookie(cookie)

        br = mechanize.Browser()

        # Cookie Jar
        br.set_cookiejar(cjNew)

        # Browser options
        br.set_handle_equiv(True)
        br.set_handle_gzip(True)
        br.set_handle_redirect(True)
        br.set_handle_referer(True)
        br.set_handle_robots(False)
        br.set_handle_refresh(mechanize._http.HTTPRefreshProcessor(), max_time=1)

        br.addheaders = [('User-agent', 'Chrome')]

        if(type == "a"):
            # print("dowloading ACM PDF...")
            br.open(self.link)
            try:
                open(fileName,'w').write(br.follow_link(text='PDF[IMG]PDF').read())
            except Exception as e:
                pass
        elif(type == "i"):
            # print("dowloading IEEE PDF...")
            br.open(self.link)
            try:
                open(fileName,'w').write(br.follow_link(nr=1).read())
            except Exception as e:
                pass


gp = GetPDF(sys.argv[1])
gp.downLoadPDF(sys.argv[3],sys.argv[2])