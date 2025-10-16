import { Link, router } from "@inertiajs/react";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import Textarea from "@/Components/Textarea";
import Comment from "@/Components/Posts/Comment";
import { useForm } from "@inertiajs/react";

export default function PostLayout({ auth, post, listView = true }) {
    const {
        data,
        setData,
        post: postComment,
        processing,
        errors,
    } = useForm({
        comment: "",
    });

    function submit(e, postId) {
        e.preventDefault();
        console.log("postId", postId);
        //alert("Comment feature is currently disabled.");
        postComment(route("comment.store", postId), {
            preserveScroll: true,
            onSuccess: () => {
                setData("comment", "");
            },
        });
    }

    return (
        <div key={post.id} className="mb-4 p-4 border rounded-md">
            <div className="w-full flex justify-between items-center my-4">
                <h2 className="text-2xl font-bold mb-2">
                    {listView === true ? (
                        <Link href={route("post.show", post.id)} className="">
                            {post.title}
                        </Link>
                    ) : (
                        post.title
                    )}
                </h2>
                {auth.user && (auth.user.id === post.user_id || auth.admin) && (
                    <div className="flex space-x-2 ml-auto">
                        <Link
                            href={route("post.edit", post.id)}
                            method="get"
                            as="button"
                            className="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                        >
                            Edit
                        </Link>
                        <button
                            onClick={() => {
                                if (
                                    confirm(
                                        "Are you sure you want to delete this post?"
                                    )
                                ) {
                                    router.delete(route("post.destroy", post.id));
                                }
                            }}
                            className="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                        >
                            Delete
                        </button>
                    </div>
                )}
                <div></div>
            </div>

            <img
                src={`https://picsum.photos/seed/${post.id}/600/400`}
                alt="Post Image"
                className="mb-4 w-full h-auto rounded-md object-cover"
            />
            <p className="mb-2">{post.content}</p>
            <p className="text-sm text-gray-600">By {post.user.name}</p>
            <div className="mt-4">
                <div className="my-2">
                    <form onSubmit={(e) => submit(e, post.id)}>
                        <InputLabel htmlFor="comment" value="comment" />

                        <Textarea
                            id="comment"
                            name="comment"
                            value={data.comment}
                            className="mt-1 block w-full"
                            autoComplete="comment"
                            onChange={(e) => setData("comment", e.target.value)}
                            required
                        />

                        <InputError message={errors.comment} className="mt-2" />
                        <div className="w-full flex justify-end my-2">
                            <PrimaryButton
                                className="ml-auto"
                                type="submit"
                                disabled={processing}
                            >
                                Add Comment
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
                <h3 className="text-lg font-semibold mb-2">
                    Comments:{" "}
                    <span className="text-sm text-gray-500">
                        ({post.comments.length})
                    </span>
                </h3>
                {listView
                    ? post.comments.map(
                          (comment, idx) =>
                              idx < 3 && (
                                  <Comment
                                      key={"comment-" + comment.id}
                                      comment={comment}
                                      auth={auth}
                                      postOwner={post.user}
                                  />
                              )
                      )
                    : post.comments.map((comment, idx) => (
                          <Comment
                              key={"comment-" + comment.id}
                              comment={comment}
                              auth={auth}
                              postOwner={post.user}
                          />
                      ))}
                {listView && post.comments.length > 3 && (
                    <Link
                        href={route("post.show", post.id)}
                        className="text-sm text-gray-500"
                    >
                        View all {post.comments.length} comments...
                    </Link>
                )}
            </div>
        </div>
    );
}
