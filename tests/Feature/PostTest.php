<?php

namespace Tests\Feature;

use App\BlogPost;
use App\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBlogPostWhenEmpty()
    {
        $response = $this->get('/posts');

        $response->assertSeeText("Uh oh, there is nothing here");
    }
    public function testBlogPostWhenTheres1Post()
    {
        //ARRANGE
        $post = $this->createDummyBlogPost();

        //ACT
        $response = $this->get('/posts');

        //ASSERT
        $response->assertSeeText('New Title');
        $response->assertSeeText('No comments yet');

        $this->assertDatabaseHas('blog_posts', [
            'title'=>'New Title'
        ]);
    }

    public function testSee1BlogPostWithComments()
    {
        //ARRANGE
        $user = $this->user();

        $post = $this->createDummyBlogPost();
        factory(Comment::class, 4)->create([
            'commentable_id' => $post->id,
            'commentable_type' => 'App\BlogPost',
            'user_id' => $user->id
        ]);

        $response = $this->get('/posts');

        $response->assertSeeText('4 comments');
    }

    public function testStoreValid()
    {
        $params = [
            'title'   => 'Valid Title',
            'content' => 'Atleast 10 char'
        ];

        $this->actingAs($this->user())
             ->post('/posts', $params)
             ->assertStatus(302)
             ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Create Success!');
    }
    public function testStoreFail()
    {
        $params = [
            'title'   => 'x',
            'content' => 'x'
        ];

        $this->actingAs($this->user())
             ->post('/posts', $params)
             ->assertStatus(302)
             ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();
        
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid()
    {
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        $this->assertDatabaseHas('blog_posts', [
            'title'=>'New Title'
        ]);

        $params = [
            'title'   => 'Different title',
            'content' => 'Another content'
        ];

        $this->actingAs($user)
             ->put("/posts/{$post->id}", $params)
             ->assertStatus(302)
             ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Update Success!');

        $this->assertDatabaseMissing('blog_posts', [
            'title'=>'New Title'
        ]);
        $this->assertDatabaseHas('blog_posts', [
            'title'=>'Different title'
        ]);
    }

    public function testDelete()
    {
        $user = $this->user();
        $post = $this->createDummyBlogPost($user->id);

        $this->assertDatabaseHas('blog_posts', [
            'title'=>'New Title'
        ]);

        $this->actingAs($user)
             ->delete("/posts/{$post->id}")
             ->assertStatus(302)
             ->assertSessionHas('status');
        
        $this->assertEquals(session('status'), 'Delete Success!');

        //$this->assertDatabaseMissing('blog_posts', [
        //    'title'=>'New Title'
        //]);
        $this->assertSoftDeleted('blog_posts', [
            'title'=>'New Title'
        ]);
    }

    private function createDummyBlogPost($userId=null): BlogPost
    {
        // $post = new BlogPost();
        // $post->title = 'New Title';
        // $post->content = 'This is Content';
        // $post->save();

        return factory(BlogPost::class)->state('new_title')->create([
            'user_id' => $userId ?? $this->user()->id
        ]);

        // return $post;
    }
    
}
