// pages/join/join.js
const app = getApp()
Page({
  data: {
    dataList: null,
    url: 'https://twitter.mbook.vip/blog/1?page=1',
    hasMore: true,
    userMessage:null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var url = 'https://twitter.mbook.vip/blog/' + options['user'] +'?page=1';
    var self = this;
    self.setData({
      url: url,
    });

      wx.request({
        url: 'https://twitter.mbook.vip/user',
        header: {
          'content-type': 'application/json'
        },
        success: function (res) {
          var userList = res.data.data;
          for (var i = 0; i < userList.length;i++){
            if (userList[i]['id'] == options['user']){
              self.setData({
                userMessage: userList[i],
              });
            }
          }
          
        }
      });
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    this.getDate();
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },
  getDate: function () {
    var self = this;
    wx.request({
      url: self.data.url,
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        self.setData({
          dataList: res.data.data,
        })
      }
    })
  },
  toDetail: function (e) {
    var index = e.currentTarget.dataset.index;
    var dataList = this.data.dataList;
    var url = dataList[index].url;
    wx.navigateTo({
      url: '/pages/index/index'
    })
  },
  loadMore: function (e) {
    var self = this;
    var url = self.data.url;
    var urlArr = url.split("=");
    var page = parseInt(urlArr[1]) + 1
    url = urlArr[0] + '=' + page;
    console.log(url);
    self.setData({
      url: url,
    })
    wx.request({
      url: url,
      header: {
        'content-type': 'application/json' // 默认值
      },
      success: function (res) {
        self.setData({
          dataList: self.data.dataList.concat(res.data.data),
        });
        if (res.data.data.length == 0) {
          console.log(res.data.data.length);
          self.setData({
            hasMore: false,
          })
        }

      }
    })
  }
})