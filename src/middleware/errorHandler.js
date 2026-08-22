/**
 * Centralized error handler middleware for Express.
 * Formats MySQL error codes into clean JSON responses.
 */
function errorHandler(err, req, res, next) {
  console.error('[Error Handler]:', err);

  // MySQL specific errors
  if (err.code === 'ER_DUP_ENTRY') {
    return res.status(409).json({
      success: false,
      error: 'Duplicate entry violation',
      details: err.sqlMessage || 'A record with this unique field already exists.'
    });
  }

  if (err.code === 'ER_NO_REFERENCED_ROW' || err.code === 'ER_NO_REFERENCED_ROW_2') {
    return res.status(400).json({
      success: false,
      error: 'Foreign key constraint failure',
      details: 'Referenced entity does not exist.'
    });
  }

  if (err.code === 'ER_ROW_IS_REFERENCED' || err.code === 'ER_ROW_IS_REFERENCED_2') {
    return res.status(400).json({
      success: false,
      error: 'Foreign key constraint failure',
      details: 'Cannot delete or update record because it is referenced by other records.'
    });
  }

  if (err.code === 'ER_BAD_FIELD_ERROR') {
    return res.status(400).json({
      success: false,
      error: 'Invalid column field',
      details: err.sqlMessage
    });
  }

  if (err.status) {
    return res.status(err.status).json({
      success: false,
      error: err.message || 'Error occurred',
      details: err.details || null
    });
  }

  res.status(500).json({
    success: false,
    error: 'Internal Server Error',
    message: err.message
  });
}

module.exports = errorHandler;
