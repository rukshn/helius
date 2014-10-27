App = Ember.Application.create();

//Routers

App.Router.map(function() {    
this.resource('index', {path : '/'} , function() { 
        this.resource('post', { path: '/p/:post_id' });
        this.resource('login');
        this.resource('about');
});
});

//Location API
App.Router.reopen({
  location: 'auto'
});

//Controllers
App.IndexController = Ember.ObjectController.extend({
  showSubmit: false,
  actions : {
    toggleSubmit : function() { //showing new submit
      this.set('showSubmit', !this.get('showSubmit'));
    }
  },
    
     submit : function(){   //submitting new story
            this.set('onsubmit' , true);
            var url = this.get("purl");
            var tagline = this.get("ptagline");
            var title = this.get("ptitle");
            this.set("ptitle" , '');
            this.set("ptagline","");
            this.set("purl","");
            
            var self= this;
            this.set('onsubmit', false);
            $.post( "/api/post.php", { purl: url, ptagline: tagline, ptitle: title })
                .done(function(data) {
                     self.set('showSubmit', !self.get('showSubmit'));
                     self.transitionTo('post', data);
                });
            }
     
});

App.BookController = Ember.ObjectController.extend({  

  islg : false,
  
  actions: {
    voteup: function(e) {   //upvoting function
      this.set('isVoted', false);
      
      var self = this;
       $.post('/api/vote.php', {post_id : e})
        .done(function(data){
          if(data == "L")
          {
            self.set('islg' , true);
          }
          else
          {
           self.set('votecount', data);
          }
        });
        console.log(e);
    },
    
    votedown: function(e) { //votedown function
      this.set('isVoted', true);
      var self = this;
      $.post('/api/vote.php', {post_id : e})
        .done(function(data){
          if(data == "L")
          {
            self.set('islg', true);
          }
          else
          {
          self.set('votecount', data);
          }
          });
        console.log(e);
    }
  }
});

App.PostController = Ember.ObjectController.extend({
  voop : false,
  
    actions: {
        voteup: function(e) { //vote up fucntion
        this.set('uposts.isVoted', false);
        var self = this;
        $.post('/api/vote.php', {post_id : e})
        .done(function(data){
          if(data == "X")
          {
            self.set('voop' , true);
          }
          else
          {
            self.set('uposts.votecount', data);
          }
          });
        console.log(e);
    },
    
    votedown: function(e) { //vote down function
      this.set('uposts.isVoted', true);
      var self = this;
      $.post('/api/vote.php', {post_id : e})
        .done(function(data){
          if(data == "X")
          {
            self.set('voop' , true);
          }
          else
          {
            self.set('uposts.votecount', data);
          }
        });
        console.log(e);
    },
      report : function(e){ //report story
        $.post('/api/report.php' , {postid : e})
        .done(function(data){
          alert('reported' + data);
        });
      },
      
      twshare: function(e){ //twitter share
        var url = e;
        window.location.href = 'http://twitter.com/share?url=http://picnicy.com/p/' + e + '&via=picnicyco&hashtags=tech';
      },
      
      fbshare : function(e){ //facebook share
        var url = e;
        window.location.href = 'https://www.facebook.com/sharer/sharer.php?u=http://picnicy.com/p/' + e;
      },
      
      submit: function(e){
        var comment = this.get("ncomment");
        var datalink = e;
        this.set("ncomment","");
        var self = this;
       
       $.post('/api/comment.php' , {comment : comment, postid : datalink })
        .done(function(){
          self.send('track');
       });
      }
  }
});

//Routes

App.PostRoute = Ember.Route.extend({ //post route
     model: function(params) {
                return Em.RSVP.hash({                    
                    uposts:  $.getJSON('/api/getpost.php' , {postid : params.post_id}),
                    ustat: $.getJSON('/api/ustatus.php'),
                    comment : $.getJSON('/api/gc.php', {postid : params.post_id})
                });
    //         return posts.findBy('datalink', params.post_id);
         },
         actions: {
       track: function() {
          this.refresh();
        }
      }


});




App.IndexRoute = Ember.Route.extend({ //index route
    
  model: function(params) {
    return Em.RSVP.hash({
      uposts: $.getJSON("/api/indexmaker.php"),
      ustat: $.getJSON("/api/ustatus.php")
    });
  }
});

//Custom text fields

Ember.TextField.reopen({ //custom text input
    attributeBindings: ['required']
});

Ember.LinkView.reopen({ //custom links
  attributeBindings: ['class']
});

Ember.TextArea.reopen({ //custom textarea
    attributeBindings: ['required' , 'class', 'rows']
});


//helpers

Ember.Handlebars.helper('format-date', function(datem) {
  return moment(datem, 'X').fromNow();
});

Ember.Handlebars.helper('format-url', function(url) {
  pathArray = String(url).split( '/' ); 
  return pathArray[2];
});

var loading = {"loading":true};


$("img").error(function () {
  $(this).unbind("error").attr("src", "http://www.piraten-oberpfalz.de/files/2009/08/orange-twitter-egg.jpg");
});


