// 404用
export const is404 = function (err) {
  return isErrWithResponseAndStatus(err) && 404 == err.response.status;
}

// 422用
export const is422 = function (err) {
  return isErrWithResponseAndStatus(err) && 422 == err.response.status
}

const isErrWithResponseAndStatus = function (err) {
  return err.response && err.response.status
}