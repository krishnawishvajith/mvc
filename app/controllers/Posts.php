<?php
class Posts extends Controller {
    private $postsModel;

    public function __construct() {
        $this->postsModel = $this->model('M_Posts');
    }


















    // Main blog page - list all posts
    public function index() {
        // Fetch recent published posts from DB
        $rows = $this->postsModel->getRecentPosts(8);

        $posts = [];
        foreach ($rows as $p) {
            // map DB fields to view-friendly array
            $image = '';
            if (!empty($p->featured_image)) {
                $fi = $p->featured_image;
                // If it's already a URL or an absolute path, use it directly, otherwise build a URL
                if (strpos($fi, 'http') === 0 || strpos($fi, '//') === 0 || strpos($fi, '/') === 0) {
                    // Normalize older saved paths that include '/public' so they map to the public URL root
                    $fi = str_replace('/public/images/blog', '/images/blog', $fi);
                    $image = $fi;
                } else {
                    $image = rtrim(URLROOT, '/') . '/images/blog/' . basename($fi);
                }
            } else {
                $image = rtrim(URLROOT, '/') . '/images/blog/default/images.png';
            }

            $posts[] = [
                'id' => $p->id,
                'title' => $p->title,
                'excerpt' => $p->excerpt ?? '',
                'content' => $p->content ?? '',
                'author' => $p->author ?? 'Admin',
                'category' => $p->category ?? 'General',
                'tags' => $p->tags ?? '',
                'image' => $image,
                'views' => $p->views ?? 0,
                'created_at' => $p->created_at ?? '',
                'status' => $p->status ?? 'published'
            ];
        }

        $data = [
            'title' => 'Blog - BookMyGround',
            'posts' => $posts
        ];

        $this->view('posts/v_blog', $data);
    }











    // Single post page
    public function show($id) {
        // Ensure id is integer
        $id = (int)$id;

        // Fetch post from DB
        $p = $this->postsModel->getPostById($id);

        if (!$p) {
            // Post not found -> redirect to blog listing
            header('Location: ' . URLROOT . '/posts');
            exit;
        }

        // Increment view count (best-effort)
        $this->postsModel->incrementViews($id);

        // Map DB object to view array
        $image = '';
        if (!empty($p->featured_image)) {
            $fi = $p->featured_image;
            if (strpos($fi, 'http') === 0 || strpos($fi, '//') === 0 || strpos($fi, '/') === 0) {
                // Normalize older saved paths that include '/public' so they map to the public URL root
                $fi = str_replace('/public/images/blog', '/images/blog', $fi);
                $image = $fi;
            } else {
                $image = rtrim(URLROOT, '/') . '/images/blog/' . basename($fi);
            }
        } else {
            $image = rtrim(URLROOT, '/') . '/images/blog/default/images.png';
        }

        $post = [
            'id' => $p->id,
            'title' => $p->title,
            'content' => $p->content ?? '',
            'author' => $p->author ?? 'Admin',
            'category' => $p->category ?? 'General',
            'image' => $image,
            'views' => ($p->views ?? 0) + 1, // reflect increment
            'created_at' => $p->created_at ?? '',
            'status' => $p->status ?? 'published'
        ];

        $data = [
            'title' => $post['title'] . ' - Blog',
            'post' => $post
        ];

        $this->view('posts/v_blog_single', $data);
    }

    // Category filter
    public function category($category) {
        // This will filter posts by category
        // For now, redirect to main blog
        header('Location: ' . URLROOT . '/posts');
        exit;
    }
}
?>