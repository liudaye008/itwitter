//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    imgUrls: [
      'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      'http://img06.tooopen.com/images/20160818/tooopen_sy_175866434296.jpg',
      'http://img06.tooopen.com/images/20160818/tooopen_sy_175833047715.jpg'
    ],
    indicatorDots: false,
    autoplay: true,
    interval: 2000,
    duration: 1000,
    proList:[
      {
        title:'test',
        desc:'descdesc',
    img:'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg'
      },
      {
        title: 'test2',
        desc: 'descdesc2',
        img: 'http://img06.tooopen.com/images/20160818/tooopen_sy_175866434296.jpg'
      }
    ]
  //事件处理函数
  },
  onLoad: function () {
    var self = this;
    wx.request({
      url: 'https://twitter.mbook.vip/user',
      header: {
        'content-type': 'application/json' // 默认值
      },
      success: function (res) {
        self.setData({
          proList: res.data.data,
        })
      }
    })
  },
  toDetail:function(e){
    var self = this;
    var index = e.currentTarget.dataset.index;
    var proList = self.data.proList;
    var id = proList[index]['id'];
    console.log(id);
    wx.navigateTo({
      url: '/pages/datail/datail?user=' + id
    })
  }
})
