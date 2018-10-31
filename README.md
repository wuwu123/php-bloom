### redis 已有的缺点

```
redis4 有布隆过滤器，但是不想升级版本，并且不在Docker下似乎安装不太好安装

redis 自带的 HyperLogLog 没有包含删除功能

原生的 setBit 操作不支持数字
```

### Tip

```
核心代码不是自己写的，在网上看到改版了一下

```

[原作者地址](http://imhuchao.com/1271.html)
