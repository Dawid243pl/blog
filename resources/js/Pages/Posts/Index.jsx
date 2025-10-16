import { Head, Link, router } from "@inertiajs/react";
import PostLayout from "@/Components/Posts/PostLayout";

export default function Index({ auth, posts }) {
    console.log("posts", posts);
    console.log("auth", auth);

    return (
        <>
            <Head title="Blog" />
            {/* <!-- Header ---> */}
            <div className="flex justify-end p-6 border-b">
                {auth.user ? (
                    <>
                        <Link
                            href={route("dashboard")}
                            className="p-4 text-md text-gray-700"
                        >
                            My Account
                        </Link>
                        <Link
                            href={route("post.create")}
                            className="p-4 text-md text-gray-700"
                        >
                            Add Post
                        </Link>
                    </>
                ) : (
                    <>
                        <Link
                            href={route("login")}
                            className="text-sm text-gray-700 dark:text-gray-500 underline"
                        >
                            Log in
                        </Link>

                        {route("register") && (
                            <Link
                                href={route("register")}
                                className="ml-4 text-sm text-gray-700 dark:text-gray-500 underline"
                            >
                                Register
                            </Link>
                        )}
                    </>
                )}
            </div>
            {/* <!-- Main Post Content ---> */}
            <div className="max-w-4xl mx-auto p-6">
                {posts.data.length === 0 && (
                    <p className="text-center">No posts available.</p>
                )}
                {posts.data.map((post) => (
                    <PostLayout
                        key={post.id}
                        auth={auth}
                        post={post}
                        listView={true}
                    ></PostLayout>
                ))}
            </div>
            {/* <!-- Pagination Controls ---> */}
            <div>
                <nav className="flex justify-center space-x-4 mb-6">
                    {posts.prev_page_url && (
                        <Link
                            href={posts.prev_page_url}
                            className="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                        >
                            Previous
                        </Link>
                    )}
                    {posts.next_page_url && (
                        <Link
                            href={posts.next_page_url}
                            className="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                        >
                            Next
                        </Link>
                    )}
                </nav>
            </div>
        </>
    );
}
