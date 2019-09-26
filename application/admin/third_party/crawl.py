from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.wait import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
# from pyquery import PyQuery as pq
from bs4 import BeautifulSoup
import multiprocessing
from multiprocessing import Pool
import time
import requests
import json
import pymysql
import re
import random
from random import choice


db = pymysql.connect('localhost', 'root', '', 'blog')
headers = {
    'user-agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36'
}

#开始爬去数据
def start(url):
    nowCount = getCount()
    res = requests.get(url, headers=headers)
    if res.status_code == 200:
        jsonData = json.loads(res.text)
        if jsonData and 'data' in jsonData.keys() and len(jsonData['data']) > 0:
            arr = []
            for item in jsonData['data']:
                url = 'https://www.toutiao.com/group/' + item['group_id']
                title = item['title']
                if isExist(title):
                    # print(title)
                    continue
                arr.append(url)
                # break
            prase_detail(arr)
    endCount = getCount()
    return  endCount - nowCount;


#爬取详情数据
def prase_detail(urls):
    start = time.time()
    pool = multiprocessing.Pool(processes=3)
    for url in urls:
        pool.apply_async(parase_html, (url,))
    pool.close()
    pool.join()  # 调用join之前，先调用close函数，否则会出错。执行完close后不会有新的进程加入到pool,join函数等待所有子进程结束
    # print('all done', int(time.time() - start), 's ', time.strftime('%Y-%m-%d %H:%M:%S', time.localtime()))

#解析网页
def parase_html(url):
    try:
        # print('开始加载:' + url)
        chrome_options = Options()
        chrome_options.add_argument('--no-sandbox')  # 解决DevToolsActivePort文件不存在的报错
        chrome_options.add_argument('window-size=1920x3000')  # 指定浏览器分辨率   尽量让完全显示所有控件，以防无法点击报错
        chrome_options.add_argument('--disable-gpu')  # 谷歌文档提到需要加上这个属性来规避bug
        # chrome_options.add_argument('--hide-scrollbars')  # 隐藏滚动条, 应对一些特殊页面
        # chrome_options.add_argument('blink-settings=imagesEnabled=false')  # 不加载图片, 提升速度
        chrome_options.add_argument('--headless')  # 浏
        browser = webdriver.Chrome(options=chrome_options)
        browser.get(url)
        # wait = WebDriverWait(browser, 10)
        # doc = pq(browser.page_source)
        # title = doc.find(_class='article-title').text()
        # content = doc('.article-content').text
        doc = BeautifulSoup(browser.page_source, 'lxml')
        title = doc.select('.article-title')[0].text
        content = doc.select('.article-content')[0]
        pattern = re.compile('.*?<img.*?src="(.*?)".*?', re.S)
        result = re.findall(pattern, str(content))
        imgs = ''
        if result and len(result) > 0:
            imgs = ','.join(result)
        # print(title);
        commit(title, imgs, content)
    except Exception as e:
        pass
        # print('parse err:', e)
    finally:
        browser.close()


#提交数据库
def commit(title, imgs, content):
    try:
        user_id = random.randint(1,1700)
        blog_type = random.randint(1,7);
        cursor = db.cursor()
        sql = " INSERT INTO blog (`user_id`,`blog_type`,`hot`,`type`,`title`,`imgs`,`content`,`create_time`) VALUES ('%d','%d',1,2,'%s','%s','%s','%s')" % (user_id,blog_type,
            title, imgs, content, int(time.time()))
        cursor.execute(sql)
        db.commit()
        # print('已爬取数量:' + str(getCount()))
        return True
    except Exception as e:
        # print('db err:', e)
        db.rollback()
        return False

#查询当前爬取数量
def getCount():
    cursor = db.cursor()
    cursor.execute(" SELECT COUNT(*) as num  from blog ")
    result = cursor.fetchone()
    return result[0]

#判断是否重复
def isExist(title):
    cursor = db.cursor()
    sql = " SELECT title from blog where title='%s'" % (title)
    row_count = cursor.execute(sql)
    if row_count > 0:
        return True
    return False


def main():
    arr = ['https://www.toutiao.com/api/pc/feed/?category=news_hot',
           'https://www.toutiao.com/api/pc/feed/?category=news_tech',
           'https://www.toutiao.com/api/pc/feed/?category=news_car',
           'https://www.toutiao.com/api/pc/feed/?category=news_finance',
           ]
    url = choice(arr)
    print(start(url))
    # while (True):
    #     url = choice(arr)
    #     print('加载类型:' + url)
    #     start(url)
    #     time.sleep(30)



if __name__ == '__main__':
    try:
        main()
    except:
        time.sleep(30)
        print('重新启动')
        main()
