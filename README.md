## 说明

由于 OpenAi 的 API 并不支持中国大陆调用它的服务，所以就得把请求代码放到国外的服务器上。

经过测试，香港地区是可以调用的。 原本以为 chat.openai.com 不支持香港和大陆注册，API 应该也不支持，没想到可以。

只有一条路由和一个控制器。所以为什么还要用 Laravel，不直接自己写个 PHP 文件呢？

因为习惯了。

API 使用的是 Stream 流式。

## 前提

- PHP 8.1+
- composer 2.2 版本以上

## 部署

```shell
git clone https://github.com/dogeow/chatgpt-api.git
cd chatgpt-api
composer i
cp .env.example .env
vim .env
# 填写最下面的 OPENAI_API_KEY 和 FRONTEND_URL
# FRONTEND_URL 是你的前端地址，用于 CORS
php artisan optimize # 可选
```

## 使用

POST https://api.example.com/ai
content: "你的问题"

return Stream
